<?php

namespace Nextend\Framework\Session\WordPress;

use Nextend\Framework\Session\AbstractStorage;
use function delete_transient;
use function get_current_user_id;
use function get_transient;
use function set_transient;

class WordPressStorage extends AbstractStorage {

    public function __construct() {
        parent::__construct(get_current_user_id());
    }

    /**
     * Load the whole session
     */
    protected function load() {
        $stored = get_transient($this->hash);

        if (!is_array($stored)) {
            $stored = array();
        }
        $this->storage = $stored;
    }

    /**
     * Store the whole session
     */
    protected function store() {
        if (count($this->storage) > 0) {
            set_transient($this->hash, $this->storage, self::$expire);
        } else {
            delete_transient($this->hash);
        }
    }
}