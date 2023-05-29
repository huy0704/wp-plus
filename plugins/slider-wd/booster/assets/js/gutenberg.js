const { registerPlugin } = wp.plugins;
const { __ } = wp.i18n;
const { PluginDocumentSettingPanel, PluginSidebarMoreMenuItem, PluginSidebar } = wp.editPost;
const { PanelBody, PanelRow, Icon } = wp.components;
const { compose } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const { useState, Fragment, useEffect } = wp.element;

const BoosterSidebarPanel = () => {
  return(
    <Fragment>
      <PluginSidebarMoreMenuItem>{twb.cta_button.section_label}</PluginSidebarMoreMenuItem>
      <PluginSidebar
        title={twb.cta_button.section_label}>
        <BoosterPanelRow/>
      </PluginSidebar>
    </Fragment>
  );
}

const BoosterSettingPanel = () => {
  return(
    <Fragment>
      <PluginDocumentSettingPanel
        title={twb.cta_button.section_label}>
        <BoosterPanelRow />
      </PluginDocumentSettingPanel>
    </Fragment>
  );
}

const BoosterPanelRow = () => {
  return (
    <PanelRow className="twb-cont">
      <OptimizeButton />
      <Dismiss />
    </PanelRow>);
}

const OptimizeButton = () => {
  return (
    <a href={twb.href}
       target="_blank"
       className={twb.cta_button.class + " twb-custom-button"}>{twb.cta_button.label}</a>
  );
}

const Dismiss = () => {
  return (
    <div className="twb-dismiss-info">
      <p>{__("You can hide this element from the ", "tenweb-booster")}
        <a href={twb.href + "&twb_dismiss=1"} target="_blank">{__('settings', "tenweb-booster")}</a></p>
    </div>
  );
}

registerPlugin('booster-sidebar-panel', {
  render: BoosterSidebarPanel,
  icon: <svg class="twb-speed-icon" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
    <g id="Group_103139" data-name="Group 103139" transform="translate(0 -0.391)">
      <path id="Path_171039" data-name="Path 171039"
            d="M.441,38.127h0a1.445,1.445,0,0,0,2.065-.037l7.924-6.038a.131.131,0,0,0,.033-.18.126.126,0,0,0-.158-.045l-9.409,4a1.426,1.426,0,0,0-.52,2.23Z"
            transform="translate(-0.028 -23.443)" fill="#fff"/>
      <path id="Path_171040" data-name="Path 171040"
            d="M5.434,48.088a1.443,1.443,0,0,1-2.063.039l0,0L.462,45.274l-.034-.029-.06-.063a1.427,1.427,0,0,1,.12-1.992,1.393,1.393,0,0,1,.4-.252L4.295,41.49,3.723,42a1.571,1.571,0,0,0-.163,2.191q.045.054.095.1l1.74,1.7a1.5,1.5,0,0,1,.039,2.1"
            transform="translate(-0.014 -30.56)" fill="#9ea3a8"/>
      <path id="Path_171041" data-name="Path 171041"
            d="M69.869,43.142h0a1.445,1.445,0,0,0-2.065.037l-7.911,6.038a.131.131,0,0,0-.033.18.126.126,0,0,0,.158.045l9.4-4a1.426,1.426,0,0,0,.52-2.23Z"
            transform="translate(-44.277 -31.469)" fill="#fff"/>
      <path id="Path_171042" data-name="Path 171042"
            d="M78,32.276a1.443,1.443,0,0,1,2.063-.039l0,0,2.907,2.851L83,35.12l.06.063a1.427,1.427,0,0,1-.12,1.992,1.393,1.393,0,0,1-.4.252l-3.407,1.448.572-.507a1.571,1.571,0,0,0,.163-2.191q-.045-.054-.095-.1l-1.742-1.7a1.5,1.5,0,0,1-.036-2.1"
            transform="translate(-57.411 -23.446)" fill="#9ea3a8"/>
      <path id="Path_171043" data-name="Path 171043"
            d="M31.607,23.5l5.172-7.19a.126.126,0,0,0,0-.176.121.121,0,0,0-.173,0l-13.2,10.025a.131.131,0,0,0-.03.18.127.127,0,0,0,.106.055h4.143a.136.136,0,0,1,.134.139.138.138,0,0,1-.025.078L22.56,33.8a.126.126,0,0,0,0,.176.121.121,0,0,0,.173,0l13.2-10.025a.131.131,0,0,0,.03-.18.127.127,0,0,0-.106-.055H31.717a.136.136,0,0,1-.134-.139.138.138,0,0,1,.025-.078"
            transform="translate(-16.668 -11.879)" fill="#9ea3a8"/>
    </g>
  </svg>
});
registerPlugin('booster-settings-panel', {
  render: BoosterSettingPanel,
  icon: ''
});