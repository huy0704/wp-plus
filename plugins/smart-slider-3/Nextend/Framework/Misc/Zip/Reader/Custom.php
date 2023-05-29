<?php

namespace Nextend\Framework\Misc\Zip\Reader;

use Nextend\Framework\Misc\Zip\ReaderInterface;
use Nextend\Framework\Notification\Notification;

class Custom implements ReaderInterface {

    private $fileHandle;

    private $file;

    public function read($path) {
        $this->file = $path;

        return $this->extract();
    }

    private function extract() {
        $extractedData = array();

        if (!is_readable(dirname($this->file))) {
            Notification::error(sprintf(n2_('%s is not readable'), dirname($this->file)));

            return false;
        }

        if (!$this->file || !is_file($this->file)) return false;
        $filesize = sprintf('%u', filesize($this->file));

        $this->fileHandle = fopen($this->file, 'rb');

        $EofCentralDirData = $this->_findEOFCentralDirectoryRecord($filesize);
        if (!is_array($EofCentralDirData)) return false;
        $centralDirectoryHeaderOffset = $EofCentralDirData['centraldiroffset'];
        for ($i = 0; $i < $EofCentralDirData['totalentries']; $i++) {
            rewind($this->fileHandle);
            fseek($this->fileHandle, $centralDirectoryHeaderOffset);
            $centralDirectoryData         = $this->_readCentralDirectoryData();
            $centralDirectoryHeaderOffset += 46 + $centralDirectoryData['filenamelength'] + $centralDirectoryData['extrafieldlength'] + $centralDirectoryData['commentlength'];
            if (!is_array($centralDirectoryData) || substr($centralDirectoryData['filename'], -1) == '/') continue;
            $data = $this->_readLocalFileHeaderAndData($centralDirectoryData);
            if (!$data) continue;

            $dir      = dirname($centralDirectoryData['filename']);
            $fileName = basename($centralDirectoryData['filename']);
            if ($dir != '.' && $dir != '') {
                if (!isset($extractedData[$dir])) {
                    $extractedData[$dir] = array();
                }
                $extractedData[$dir][$fileName] = $data;
            } else {
                $extractedData[$fileName] = $data;
            }
        }
        fclose($this->fileHandle);

        return $extractedData;
    }

    private function _findEOFCentralDirectoryRecord($filesize) {
        fseek($this->fileHandle, $filesize - 22);
        $EofCentralDirSignature = unpack('Vsignature', fread($this->fileHandle, 4));
        if ($EofCentralDirSignature['signature'] != 0x06054b50) {
            $maxLength = 65535 + 22;
            $maxLength > $filesize && $maxLength = $filesize;
            fseek($this->fileHandle, $filesize - $maxLength);
            $searchPos = ftell($this->fileHandle);
            while ($searchPos < $filesize) {
                fseek($this->fileHandle, $searchPos);
                $sigData = unpack('Vsignature', fread($this->fileHandle, 4));
                if ($sigData['signature'] == 0x06054b50) {
                    break;
                }
                $searchPos++;
            }
        }
        $EofCentralDirData = unpack('vdisknum/vdiskstart/vcentraldirnum/vtotalentries/Vcentraldirsize/Vcentraldiroffset/vcommentlength', fread($this->fileHandle, 18));

        return $EofCentralDirData;
    }

    private function _readCentralDirectoryData() {
        $centralDirectorySignature = unpack('Vsignature', fread($this->fileHandle, 4));
        if ($centralDirectorySignature['signature'] != 0x02014b50) return false;
        $centralDirectoryData = fread($this->fileHandle, 42);
        $centralDirectoryData = unpack('vmadeversion/vextractversion/vflag/vcompressmethod/vmodtime/vmoddate/Vcrc/Vcompressedsize/Vuncompressedsize/vfilenamelength/vextrafieldlength/vcommentlength/vdiskstart/vinternal/Vexternal/Vlocalheaderoffset', $centralDirectoryData);
        $centralDirectoryData['filenamelength'] && $centralDirectoryData['filename'] = fread($this->fileHandle, $centralDirectoryData['filenamelength']);

        return $centralDirectoryData;
    }

    private function _readLocalFileHeaderAndData($centralDirectoryData) {
        fseek($this->fileHandle, $centralDirectoryData['localheaderoffset']);
        $localFileHeaderSignature = unpack('Vsignature', fread($this->fileHandle, 4));
        if ($localFileHeaderSignature['signature'] != 0x04034b50) return false;
        $localFileHeaderData = fread($this->fileHandle, 26);
        $localFileHeaderData = unpack('vextractversion/vflag/vcompressmethod/vmodtime/vmoddate/Vcrc/Vcompressedsize/Vuncompressedsize/vfilenamelength/vextrafieldlength', $localFileHeaderData);
        $localFileHeaderData['filenamelength'] && $localFileHeaderData['filename'] = fread($this->fileHandle, $localFileHeaderData['filenamelength']);
        if (!$this->_checkLocalFileHeaderAndCentralDir($localFileHeaderData, $centralDirectoryData)) return false;

        if ($localFileHeaderData['flag'] & 1) return false;
        $compressedData = fread($this->fileHandle, $localFileHeaderData['compressedsize']);
        $data           = $this->_unCompressData($compressedData, $localFileHeaderData['compressmethod']);

        if (crc32($data) != $localFileHeaderData['crc'] || strlen($data) != $localFileHeaderData['uncompressedsize']) return false;

        return $data;
    }

    private function _unCompressData($data, $compressMethod) {
        if (!$compressMethod) return $data;
        switch ($compressMethod) {
            case 8 :
                $data = gzinflate($data);
                break;
            default :
                return false;
                break;
        }

        return $data;
    }

    private function _checkLocalFileHeaderAndCentralDir($localFileHeaderData, $centralDirectoryData) {
        return true;
    }
}