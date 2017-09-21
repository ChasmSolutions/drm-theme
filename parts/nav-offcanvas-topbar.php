<?php
/*
 * By default, Foundation will use .title-bar for small, and .top-bar for
 * medium up
 */
?>

<div class="title-bar" data-responsive-toggle="top-bar-menu" data-hide-for="medium">
    <div class="title-bar-left">
        <button class="menu-icon" type="button" data-open="off-canvas">
            <img src="<?php echo get_template_directory_uri() . "/assets/images/hamburger.svg" ?>">
        </button>
        <div class="title-bar-title"><?php _e( "Disciple Tools" ); ?></div>
    </div>
    <div class="title-bar-right">
        <?php if (is_post_type_archive( "contacts" ) || is_post_type_archive( "groups" )): ?>
        <button data-open="filters-modal">
            <img title="<?php _e( "Filters" ); ?>" src="<?php echo get_template_directory_uri() . "/assets/images/filter.svg" ?>">
        </button>
        <?php endif; ?>
        <a href="<?php echo home_url( '/notifications' ); ?>">
            <img title="<?php _e( "Notifications" ); ?>" src="<?php echo get_template_directory_uri() . "/assets/images/bell.svg" ?>">
        </a>
        <a href="<?php echo home_url( '/settings') ?>">
            <img title="<?php _e( "settings" ); ?>" src="<?php echo get_template_directory_uri() . "/assets/images/gear.svg" ?>">
        </a>
    </div>
</div>


<div data-sticky-container class="hide-for-small-only">
    <div class="top-bar" id="top-bar-menu"
         data-sticky style="width:100%;margin-top:0">
      <div class="top-bar-left">
        <ul class="menu">
          <li data-toggle="off-canvas" class="center-items">
            <button>
              <img src="<?php echo get_template_directory_uri() . "/assets/images/hamburger.svg" ?>">
            </button>
          </li>
        <?php disciple_tools_top_nav_desktop(); ?>
        </ul>
      </div>
      <div class="top-bar-right">
        <ul class="dropdown menu" data-dropdown-menu>
          <li><a href="<?php echo home_url( '/notifications' ); ?>"><span class="badge alert notification-count" style="display:none;">&nbsp;</span> Notifications</a></li>
          <li>
            <a href="<?php echo home_url( '/' ) . 'settings/'; ?>"><i class="fi-widget"></i></a>
            <ul class="menu vertical">
              <li><a href="<?php echo home_url( '/' ) . 'settings/'; ?>">Settings</a></li>
          <?php if( user_can( get_current_user_id(), 'read' ) ) : ?><li><a href="<?php echo get_admin_url(); ?>"><?php _e( "Admin" ); ?></a></li><?php endif; ?>
              <li><a href="<?php echo wp_logout_url()?>">Log Off</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
</div>

