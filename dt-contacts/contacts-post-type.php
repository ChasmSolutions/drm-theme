<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly.

/**
 * Disciple_Tools Plugin Contacts Post Type Class
 * All functionality pertaining to contacts post types in Disciple_Tools.
 *
 * @package  Disciple_Tools
 * @category Plugin
 * @author   Chasm.Solutions & Kingdom.Training
 * @since    0.1.0
 */

/**
 * Class Disciple_Tools_Contact_Post_Type
 */
class Disciple_Tools_Contact_Post_Type
{
    /**
     * The post type token.
     *
     * @access public
     * @since  0.1.0
     * @var    string
     */
    public $post_type;

    /**
     * The post type singular label.
     *
     * @access public
     * @since  0.1.0
     * @var    string
     */
    public $singular;

    /**
     * The post type plural label.
     *
     * @access public
     * @since  0.1.0
     * @var    string
     */
    public $plural;

    public $search_items;

    /**
     * The post type args.
     *
     * @access public
     * @since  0.1.0
     * @var    array
     */
    public $args;

    /**
     * The taxonomies for this post type.
     *
     * @access public
     * @since  0.1.0
     * @var    array
     */
    public $taxonomies;

    /**
     * Disciple_Tools_Admin_Menus The single instance of Disciple_Tools_Admin_Menus.
     *
     * @var    object
     * @access private
     * @since  0.1.0
     */
    private static $_instance = null;

    /**
     * Main Disciple_Tools_Contact_Post_Type Instance
     * Ensures only one instance of Disciple_Tools_Contact_Post_Type is loaded or can be loaded.
     *
     * @since  0.1.0
     * @static
     * @return Disciple_Tools_Contact_Post_Type instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    } // End instance()

    /**
     * Disciple_Tools_Contact_Post_Type constructor.
     *
     * @param string $post_type
     * @param string $singular
     * @param string $plural
     * @param array  $args
     * @param array  $taxonomies
     */
    public function __construct( $post_type = 'contacts', $singular = '', $plural = '', $args = [], $taxonomies = [] ) {
        $this->post_type = 'contacts';
        $this->singular = _x( 'Contact', 'singular of contact', 'disciple_tools' );
        $this->plural = _x( 'Contacts', 'plural of contact', 'disciple_tools' );
        $this->search_items = sprintf( _x( "Search %s", "Search 'something'", 'disciple_tools' ), $this->plural );
        $this->args = [ 'menu_icon' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48ZyBjbGFzcz0ibmMtaWNvbi13cmFwcGVyIiBmaWxsPSIjZmZmZmZmIj48cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNOSwxMmMyLjc1NywwLDUtMi4yNDMsNS01VjVjMC0yLjc1Ny0yLjI0My01LTUtNVM0LDIuMjQzLDQsNXYyQzQsOS43NTcsNi4yNDMsMTIsOSwxMnoiPjwvcGF0aD4gPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTTE1LjQyMywxNS4xNDVDMTQuMDQyLDE0LjYyMiwxMS44MDYsMTQsOSwxNHMtNS4wNDIsMC42MjItNi40MjQsMS4xNDZDMS4wMzUsMTUuNzI5LDAsMTcuMjMzLDAsMTguODg2VjI0IGgxOHYtNS4xMTRDMTgsMTcuMjMzLDE2Ljk2NSwxNS43MjksMTUuNDIzLDE1LjE0NXoiPjwvcGF0aD4gPHJlY3QgZGF0YS1jb2xvcj0iY29sb3ItMiIgeD0iMTYiIHk9IjMiIGZpbGw9IiNmZmZmZmYiIHdpZHRoPSI4IiBoZWlnaHQ9IjIiPjwvcmVjdD4gPHJlY3QgZGF0YS1jb2xvcj0iY29sb3ItMiIgeD0iMTYiIHk9IjgiIGZpbGw9IiNmZmZmZmYiIHdpZHRoPSI4IiBoZWlnaHQ9IjIiPjwvcmVjdD4gPHJlY3QgZGF0YS1jb2xvcj0iY29sb3ItMiIgeD0iMTkiIHk9IjEzIiBmaWxsPSIjZmZmZmZmIiB3aWR0aD0iNSIgaGVpZ2h0PSIyIj48L3JlY3Q+PC9nPjwvc3ZnPg==' ];
        $this->taxonomies = $taxonomies = [];

        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'contacts_rewrites_init' ] );
        add_filter( 'post_type_link', [ $this, 'contacts_permalink' ], 1, 3 );
        add_filter( 'dt_get_post_type_settings', [ $this, 'get_post_type_settings_hook' ], 10, 2 );

    } // End __construct()

    /**
     * Register the post type.
     *
     * @access public
     * @return void
     */
    public function register_post_type() {
        $labels = [
            'name'                  => $this->plural,
            'singular_name'         => $this->singular,
            'menu_name'             => $this->plural,
            'search_items'          => $this->search_items,
        ];
        $rewrite = [
            'slug'       => 'contacts',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];
        $capabilities = [
            'create_posts'        => 'do_not_allow',
            'edit_post'           => 'access_contacts',
            'read_post'           => 'access_contacts',
            'delete_post'         => 'delete_any_contacts',
            'delete_others_posts' => 'delete_any_contacts',
            'delete_posts'        => 'delete_any_contacts',
            'edit_posts'          => 'access_contacts',
            'edit_others_posts'   => 'update_any_contacts',
            'publish_posts'       => 'create_contacts',
            'read_private_posts'  => 'view_any_contacts',
        ];
        $defaults = [
            'label'                 => $this->singular,
            'description'           => '',
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => $rewrite,
            'capabilities'          => $capabilities,
            'capability_type'       => 'contact',
            'has_archive'           => true, //$archive_slug,
            'hierarchical'          => false,
            'supports'              => [ 'title', 'comments' ],
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-groups',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'exclude_from_search'   => true,
            'show_in_rest'          => false,
        ];

        $args = wp_parse_args( $this->args, $defaults );

        register_post_type( $this->post_type, $args );
    } // End register_post_type()


    public function get_contact_field_defaults( $post_id = null, $include_current_post = null ){
        $fields = [];

        $fields['assigned_to'] = [
            'name'        => _x( 'Assigned To', 'field name', 'disciple_tools' ),
            'description' => _x( 'Select the main person who is responsible for reporting on this contact.', 'field description', 'disciple_tools' ),
            'type'        => 'user_select',
            'default'     => '',
            'section'     => 'status',
        ];

        // Status Section
        $fields['overall_status'] = [
            'name'        => _x( 'Contact Status', 'Contact Status field name', 'disciple_tools' ),
            'description' => _x( 'This is where you set the current status of the contact.', 'Contact Status field description', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'new'   => [
                    "label" => _x( 'New Contact', 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "The contact is new in the system.", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#F43636",
                ],
                'unassignable' => [
                    "label" => _x( 'Not Ready', 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "There is not enough information to move forward with the contact at this time.", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#FF9800",
                ],
                'unassigned'   => [
                    "label" => _x( 'Dispatch Needed', 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "This contact needs to be assigned to a multiplier.", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#F43636",
                ],
                'assigned'     => [
                    "label" => _x( "Waiting to be accepted", 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "The contact has been assigned to someone, but has not yet been accepted by that person.", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#FF9800",
                ],
                'active'       => [
                    "label" => _x( 'Active', 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "The contact is progressing and/or continually being updated.", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#4CAF50",
                ],
                'paused'       => [
                    "label" => _x( 'Paused', 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "This contact is currently on hold (i.e. on vacation or not responding).", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#FF9800",
                ],
                'closed'       => [
                    "label" => _x( 'Closed', 'Contact Status label', 'disciple_tools' ),
                    "description" => _x( "This contact has made it known that they no longer want to continue or you have decided not to continue with him/her.", "Contact Status field description", 'disciple_tools' ),
                    "color" => "#F43636",
                ],
            ],
            'section'     => 'status',
            'customizable' => 'add_only'
        ];

        //these fields must stay in order of importance
        $fields['seeker_path'] = [
            'name'        => _x( 'Seeker Path', 'Seeker Path field name', 'disciple_tools' ),
            'description' => _x( "Set the status of your progression with the contact. These are the steps that happen in a specific order to help a contact move forward.", 'Seeker Path field description', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'none'        => [
                  "label" => _x( 'Contact Attempt Needed', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "Communication with this contact needs to be attempted.", "Contact Attempt Needed field description", 'disciple_tools' ),
                ],
                'attempted'   => [
                  "label" => _x( 'Contact Attempted', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "Communication with this contact was attempted.", "Contact Attempted field description", 'disciple_tools' ),
                ],
                'established' => [
                  "label" => _x( 'Contact Established', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "Communication with this contact was established.", "Contact Established field description", 'disciple_tools' ),
                ],
                'scheduled'   => [
                  "label" => _x( 'First Meeting Scheduled', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "The first meeting with this contact has been scheduled.", "Seeker Path field description", 'disciple_tools' ),
                ],
                'met'         => [
                  "label" => _x( 'First Meeting Complete', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "The first meeting with this contact has been completed.", "Seeker Path field description", 'disciple_tools' ),
                ],
                'ongoing'     => [
                  "label" => _x( 'Ongoing Meetings', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "This contact is continuing in meetings.", "Seeker Path field description", 'disciple_tools' ),
                ],
                'coaching'    => [
                  "label" => _x( 'Being Coached', 'Seeker Path label', 'disciple_tools' ),
                  "description" => _x( "This contact is being coached.", "Seeker Path field description", 'disciple_tools' ),
                ],
            ],
            'section'     => 'status',
            'customizable' => 'add_only'
        ];

        $fields['requires_update'] = [
            'name'        => _x( 'Requires Update', 'field name', 'disciple_tools' ),
            'description' => _x( 'The contact requires an update.', 'field description', 'disciple_tools' ),
            'type'        => 'boolean',
            'default'     => false,
            'section'     => 'status',
        ];

        $id = $post->ID ?? $post_id;
        if ( $include_current_post && ( $id || ( isset( $post->ID ) && $post->post_status != 'auto-draft' ) ) ) { // if being called for a specific record or new record.
            // Contact Channels Section
            $methods   = $this->contact_fields( $id );
            foreach ( $methods as $k => $v ) {
                $fields[ $k ] = [
                    'name'        => ucwords( $v['name'] ),
                    'description' => '',
                    'type'        => 'text',
                    'default'     => '',
                    'section'     => 'info',
                ];
            }
        }

        $fields["milestones"] = [
            "name"    => _x( 'Faith Milestones', 'Faith Milestone field name', 'disciple_tools' ),
            "description" => _x( 'Assign which milestones the contact has reached in their faith journey. These are points in a contact’s spiritual journey worth celebrating but can happen in any order.', 'Faith Milestone field description', 'disciple_tools' ),
            "type"    => "multi_select",
            "default" => [
                "milestone_has_bible"     => [
                  "label" => _x( 'Has Bible', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact has a bible.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_reading_bible" => [
                  "label" => _x( 'Reading Bible', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact is reading the bible.", 'Faith Milestone field description', 'disciple_tools' )
                ],
                "milestone_belief"        => [
                  "label" => _x( 'States Belief', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact states belief. i.e. they have repented and believed.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_can_share"     => [
                  "label" => _x( 'Can Share Gospel/Testimony', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact has been trained to share the Gospel and their testimony.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_sharing"       => [
                  "label" => _x( 'Sharing Gospel/Testimony', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact is sharing the Gospel and their testimony.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_baptized"      => [
                  "label" => _x( 'Baptized', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact has been baptized.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_baptizing"     => [
                  "label" => _x( 'Baptizing', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact is baptizing others.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_in_group"      => [
                  "label" => _x( 'In Church/Group', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact is in a church or group.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
                "milestone_planting"      => [
                  "label" => _x( 'Starting Churches', 'milestone field label', 'disciple_tools' ),
                  "description" => _x( "This contact is planting Churches.", 'Faith Milestone field description', 'disciple_tools' ),
                ],
            ],
            "customizable" => "add_only"
        ];

        $fields['baptism_date'] = [
            'name'        => _x( 'Baptism Date', 'field name', 'disciple_tools' ),
            'description' => _x( 'The date the contact was baptised.', 'Baptism Date field description', 'disciple_tools' ),
            'type'        => 'date',
            'default'     => '',
            'section'     => 'misc',
        ];

        $fields['baptism_generation'] = [
            'name'        => _x( 'Baptism Generation', 'field name', 'disciple_tools' ),
            'type'        => 'text',
            'default'     => '',
            'section'     => 'misc',
        ];

        // Misc Information fields

        $fields['gender'] = [
            'name'        => _x( 'Gender', 'field name', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'not-set' => [ "label" => '' ],
                'male'    => [ "label" => _x( 'Male', 'Gender label', 'disciple_tools' ) ],
                'female'  => [ "label" => _x( 'Female', 'Gender label', 'disciple_tools' ) ],
            ],
            'section'     => 'misc',
        ];
        $fields['age'] = [
            'name'        => _x( 'Age', 'field name', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'not-set' => [ "label" => '' ],
                '<19'     => [ "label" => _x( 'Under 18 years old', 'Age label', 'disciple_tools' ) ],
                '<26'     => [ "label" => _x( '18-25 years old', 'Age label', 'disciple_tools' ) ],
                '<41'     => [ "label" => _x( '26-40 years old', 'Age label', 'disciple_tools' ) ],
                '>41'     => [ "label" => _x( 'Over 40 years old', 'Age label', 'disciple_tools' ) ],
            ],
            'section'     => 'misc',
        ];

        $fields["reason_unassignable"] = [
            'name'        => _x( 'Reason Not Ready', 'field name', 'disciple_tools' ),
            'description' => _x( 'The main reason the contact is not ready to be assigned to a user.', 'Reason Not Ready field description', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'none'         => [
                    "label" => '',
                ],
                'insufficient' => [
                    "label" => _x( 'Insufficient Contact Information', 'Reason Not Ready label', 'disciple_tools' )
                ],
                'location'     => [
                    "label" => _x( 'Unknown Location', 'Reason Not Ready label', 'disciple_tools' )
                ],
                'media'        => [
                    "label" => _x( 'Only wants media', 'Reason Not Ready label', 'disciple_tools' )
                ],
                'outside_area' => [
                    "label" => _x( 'Outside Area', 'Reason Not Ready label', 'disciple_tools' )
                ],
                'needs_review' => [
                    "label" => _x( 'Needs Review', 'Reason Not Ready label', 'disciple_tools' )
                ],
                'awaiting_confirmation' => [
                    "label" => _x( 'Waiting for Confirmation', 'Reason Not Ready label', 'disciple_tools' )
                ],
            ],
            'section'     => 'misc',
            'customizable' => 'all'
        ];

        $fields['reason_paused'] = [
            'name'        => _x( 'Reason Paused', 'field name', 'disciple_tools' ),
            'description' => _x( 'A paused contact is one you are not currently interacting with but expect to in the future.', 'Reason Paused description', 'disciple_tools' ),
            'type'        => 'key_select',
            'default' => [
                'none'                 => [ "label" => '' ],
                'vacation'             => [ "label" => _x( 'Contact on vacation', 'Reason Paused label', 'disciple_tools' ) ],
                'not_responding'       => [ "label" => _x( 'Contact not responding', 'Reason Paused label', 'disciple_tools' ) ],
                'not_available'        => [ "label" => _x( 'Contact not available', 'Reason Paused label', 'disciple_tools' ) ],
                'little_interest'      => [ "label" => _x( 'Contact has little interest/hunger', 'Reason Paused label', 'disciple_tools' ) ],
                'no_initiative'        => [ "label" => _x( 'Contact shows no initiative', 'Reason Paused label', 'disciple_tools' ) ],
                'questionable_motives' => [ "label" => _x( 'Contact has questionable motives', 'Reason Paused label', 'disciple_tools' ) ],
                'ball_in_their_court'  => [ "label" => _x( 'Ball is in the contact\'s court', 'Reason Paused label', 'disciple_tools' ) ],
                'wait_and_see'         => [ "label" => _x( 'We want to see if/how the contact responds to automated text messages', 'Reason Paused label', 'disciple_tools' ) ],
            ],
            'section'     => 'misc',
            'customizable' => 'all'
        ];

        $fields['reason_closed'] = [
            'name'        => _x( 'Reason Closed', 'field name', 'disciple_tools' ),
            'description' => _x( "A closed contact is one you can't or don't wish to interact with.", 'Reason Closed field description', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'none'                 => [ "label" => '' ],
                'duplicate'            => [ "label" => _x( 'Duplicate', 'Reason Closed label', 'disciple_tools' ) ],
                'insufficient'         => [ "label" => _x( 'Insufficient contact info', 'Reason Closed label', 'disciple_tools' ) ],
                'denies_submission'    => [ "label" => _x( 'Denies submitting contact request', 'Reason Closed label', 'disciple_tools' ) ],
                'hostile_self_gain'    => [ "label" => _x( 'Hostile, playing games or self gain', 'Reason Closed label', 'disciple_tools' ) ],
                'apologetics'          => [ "label" => _x( 'Only wants to argue or debate', 'Reason Closed label', 'disciple_tools' ) ],
                'media_only'           => [ "label" => _x( 'Just wanted media or book', 'Reason Closed label', 'disciple_tools' ) ],
                'no_longer_interested' => [ "label" => _x( 'No longer interested', 'Reason Closed label', 'disciple_tools' ) ],
                'no_longer_responding' => [ "label" => _x( 'No longer responding', 'Reason Closed label', 'disciple_tools' ) ],
                'already_connected'    => [ "label" => _x( 'Already in church or connected with others', 'Reason Closed label', 'disciple_tools' ) ],
                'transfer'             => [ "label" => _x( 'Transferred contact to partner', 'Reason Closed label', 'disciple_tools' ) ],
                'martyred'             => [ "label" => _x( 'Martyred', 'Reason Closed label', 'disciple_tools' ) ],
                'moved'                => [ "label" => _x( 'Moved or relocated', 'Reason Closed label', 'disciple_tools' ) ],
                'unknown'              => [ "label" => _x( 'Unknown', 'Reason Closed label', 'disciple_tools' ) ]
            ],
            'section'     => 'misc',
            'customizable' => 'all'
        ];
        $fields['accepted'] = [
            'name'        => _x( 'Accepted', 'field name', 'disciple_tools' ),
            'type'        => 'boolean',
            'default'     => false,
            'section'     => 'status',
            'hidden'      => true
        ];

        $sources_default = [];
        foreach ( dt_get_option( 'dt_site_custom_lists' )['sources'] as $key => $value ) {
            if ( isset( $value['enabled'] ) && $value["enabled"] === false ) {
                $value["deleted"] = true;
            }
            $sources_default[ $key ] = $value;
        }

        $fields['sources'] = [
            'name'        => _x( 'Sources', 'field name', 'disciple_tools' ),
            'description' => _x( 'The website, event or location this contact came from.', 'Sources field description', 'disciple_tools' ),
            'type'        => 'multi_select',
            'default'     => $sources_default,
            'section'     => 'misc',
            'customizable' => 'all'
        ];

        $fields["source_details"] = [
            "name" => _x( "Source Details", 'field name', 'disciple_tools' ),
            'type' => 'text',
            'default' => '',
            'section'     => 'misc',
        ];

        // contact buttons
        $fields['quick_button_no_answer'] = [
            'name'        => _x( 'No Answer', 'quick button response name', 'disciple_tools' ),
            'description' => _x( 'Call was attempted by not answered', 'quick button response description', 'disciple_tools' ),
            'type'        => 'number',
            'default'     => 0,
            'section'     => 'quick_buttons',
            'icon'        => "no-answer.svg",
        ];
        $fields['quick_button_contact_established'] = [
            'name'        => _x( 'Contact Established', 'quick button response name', 'disciple_tools' ),
            'description' => _x( 'Contact was successfully established', 'quick button response description', 'disciple_tools' ),
            'type'        => 'number',
            'default'     => 0,
            'section'     => 'quick_buttons',
            'icon'        => "successful-conversation.svg",
        ];
        $fields['quick_button_meeting_scheduled'] = [
            'name'        => _x( 'Meeting Scheduled', 'quick button response name', 'disciple_tools' ),
            'description' => _x( 'A meeting has been scheduled', 'quick button response description', 'disciple_tools' ),
            'type'        => 'number',
            'default'     => 0,
            'section'     => 'quick_buttons',
            'icon'        => "meeting-scheduled.svg",
        ];
        $fields['quick_button_meeting_complete'] = [
            'name'        => _x( 'Meeting Complete', 'quick button response name', 'disciple_tools' ),
            'description' => _x( 'A meeting was completed', 'quick button response description', 'disciple_tools' ),
            'type'        => 'number',
            'default'     => 0,
            'section'     => 'quick_buttons',
            'icon'        => "meeting-complete.svg",
        ];
        $fields['quick_button_no_show'] = [
            'name'        => _x( 'Meeting No-show', 'quick button response name', 'disciple_tools' ),
            'description' => _x( 'Did not attend a scheduled meeting', 'quick button response description', 'disciple_tools' ),
            'type'        => 'number',
            'default'     => 0,
            'section'     => 'quick_buttons',
            'icon'        => "no-show.svg",
        ];

        $fields['corresponds_to_user'] = [
            'name' => _x( 'Corresponds to user', 'field name', 'disciple_tools' ),
            'description' => _x( 'The id of the user this contact corresponds to', 'field description', 'disciple_tools' ),
            'type' => 'number',
            'default' => 0,
            'section' => 'misc'
        ];
        $fields["type"] = [
            'name'        => _x( 'Contact type', 'field name', 'disciple_tools' ),
            'type'        => 'key_select',
            'default'     => [
                'media'    => [ "label" => _x( 'Media', 'Type of contact label', 'disciple_tools' ) ],
                'next_gen' => [ "label" => _x( 'Next Generation', 'Type of contact label', 'disciple_tools' ) ],
                'user'     => [ "label" => _x( 'User', 'Type of contact label', 'disciple_tools' ) ]
            ],
            'section'     => 'misc',
            'hidden'      => true
        ];
        $fields["last_modified"] =[
            'name' => 'Last modified', //system string does not need translation
            'type' => 'number',
            'default' => 0,
            'section' => 'admin'
        ];
        $fields["duplicate_data"] = [
            "name" => 'Duplicates', //system string does not need translation
            'type' => 'array',
            'default' => [],
            'section' => 'admin',
            "hidden" => true
        ];
        $fields['tags'] = [
            'name'        => _x( 'Tags', 'field name', 'disciple_tools' ),
            'description' => _x( 'A useful way to group related items and can help group contacts associated with noteworthy characteristics. e.g. business owner, sports lover. The contacts can also be filtered using these tags.', 'field description', 'disciple_tools' ),
            'type'        => 'multi_select',
            'default'     => [],
            'section'     => 'misc',
        ];

        $fields["follow"] = [
            'name'        => _x( 'Follow', 'field name', 'disciple_tools' ),
            'type'        => 'multi_select',
            'default'     => [],
            'section'     => 'misc',
            'hidden'      => true
        ];
        $fields["unfollow"] = [
            'name'        => _x( 'Un-Follow', 'field name', 'disciple_tools' ),
            'type'        => 'multi_select',
            'default'     => [],
            'section'     => 'misc',
            'hidden'      => true
        ];
        $fields["duplicate_of"] = [
            "name" => "Duplicate of", //system string does not need translation
            "type" => "text",
            "default" => '',
            "hidden" => true
        ];
        $fields["relation"] = [
            "name" => _x( "Relation", 'field name', 'disciple_tools' ),
            "description" => _x( "Relationship this contact has with another contact in the system.", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "contacts",
            "p2p_direction" => "any",
            "p2p_key" => "contacts_to_relation"
        ];
        $fields["coached_by"] = [
            "name" => _x( "Coached by", 'field name', 'disciple_tools' ),
            "description" => _x( "Who is coaching this contact", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "contacts",
            "p2p_direction" => "from",
            "p2p_key" => "contacts_to_contacts"
        ];
        $fields["coaching"] = [
            "name" => _x( "Coached", 'field name', 'disciple_tools' ),
            "description" => _x( "Who is this contact coaching", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "contacts",
            "p2p_direction" => "to",
            "p2p_key" => "contacts_to_contacts"
        ];
        $fields["baptized_by"] = [
            "name" => _x( "Baptized by", 'field name', 'disciple_tools' ),
            "description" => _x( "Who baptized this contact", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "contacts",
            "p2p_direction" => "from",
            "p2p_key" => "baptizer_to_baptized"
        ];
        $fields["baptized"] = [
            "name" => _x( "Baptized", 'field name', 'disciple_tools' ),
            "description" => _x( "Who this contact has baptized", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "contacts",
            "p2p_direction" => "to",
            "p2p_key" => "baptizer_to_baptized"
        ];
        $fields["people_groups"] = [
            "name" => __( 'People Groups', 'disciple_tools' ),
            "description" => _x( "People Groups this contact belongs to.", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "peoplegroups",
            "p2p_direction" => "from",
            "p2p_key" => "contacts_to_peoplegroups"
        ];
        $fields["groups"] = [
            "name" => _x( "Groups", 'field name', 'disciple_tools' ),
            "description" => _x( "Groups this contact is a member of.", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "groups",
            "p2p_direction" => "from",
            "p2p_key" => "contacts_to_groups"
        ];
        $fields["subassigned"] = [
            "name" => _x( "Sub-assigned to", 'field name', 'disciple_tools' ),
            "description" => _x( "This is someone working alongside of the main person assigned to the contact. You may find that you are partnering with others in your discipleship relationships. Only one person can be assigned while multiple people can be sub-assigned.", 'field description', 'disciple_tools' ),
            "type" => "connection",
            "post_type" => "contacts",
            "p2p_direction" => "to",
            "p2p_key" => "contacts_to_subassigned"
        ];
        $fields['location_grid'] = [
            'name'        => _x( 'Locations', 'field name', 'disciple_tools' ),
            'description' => _x( 'The general location where this contact is located.', 'field description', 'disciple_tools' ),
            'type'        => 'location',
            'default'     => [],
        ];
        $fields['location_lnglat'] = [
            'name'        => _x( 'Coordinates', 'field name', 'disciple_tools' ),
            'type'        => 'location',
            'default'     => [],
            'hidden' => true
        ];
        $fields['tasks'] = [
            'name' => _x( 'Tasks', 'field name', 'disciple_tools' ),
            'description' => _x( 'Tasks related to this contact.', 'field description', 'disciple_tools' ),
            'type' => 'post_user_meta',
        ];

        return $fields;
    }

    /**
     * Get the settings for the custom fields.
     *
     * @param bool $include_current_post
     * @param int|null $post_id
     * @param bool $with_deleted_options
     * @param bool $load_from_cache
     *
     * @return mixed
     */
    public function get_custom_fields_settings( $include_current_post = true, int $post_id = null, $with_deleted_options = false, $load_from_cache = true ) {

        $cache_with_deleted = $with_deleted_options ? "_with_deleted" : "";
        $cached = wp_cache_get( "contact_field_settings" . $cache_with_deleted );
        if ( $load_from_cache && $cached ){
            return $cached;
        }
        $fields = $this->get_contact_field_defaults( $post_id, $include_current_post );
        $fields = apply_filters( 'dt_custom_fields_settings', $fields, "contacts" );
        foreach ( $fields as $field_key => $field ){
            if ( $field["type"] === "key_select" || $field["type"] === "multi_select" ){
                foreach ( $field["default"] as $option_key => $option_value ){
                    if ( !is_array( $option_value )){
                        $fields[$field_key]["default"][$option_key] = [ "label" => $option_value ];
                    }
                }
            }
        }
        $custom_field_options = dt_get_option( "dt_field_customizations" );
        if ( isset( $custom_field_options["contacts"] )){
            foreach ( $custom_field_options["contacts"] as $key => $field ){
                $field_type = $field["type"] ?? $fields[$key]["type"] ?? "";
                if ( $field_type ) {
                    if ( !isset( $fields[ $key ] ) ) {
                        $fields[ $key ] = $field;
                    } else {
                        if ( isset( $field["name"] ) ) {
                            $fields[ $key ]["name"] = $field["name"];
                        }
                        if ( isset( $field["tile"] ) ) {
                            $fields[ $key ]["tile"] = $field["tile"];
                        }
                        if ( $field_type === "key_select" || $field_type === "multi_select" ) {
                            if ( isset( $field["default"] ) ) {
                                $fields[ $key ]["default"] = array_replace_recursive( $fields[ $key ]["default"], $field["default"] );
                            }
                        }
                    }
                    if ( $field_type === "key_select" || $field_type === "multi_select" ) {
                        if ( isset( $field["order"] ) ) {
                            $with_order = [];
                            foreach ( $field["order"] as $ordered_key ) {
                                $with_order[ $ordered_key ] = [];
                            }
                            foreach ( $fields[ $key ]["default"] as $option_key => $option_value ) {
                                $with_order[ $option_key ] = $option_value;
                            }
                            $fields[ $key ]["default"] = $with_order;
                        }
                    }
                }
            }
        }
        if ( $with_deleted_options === false ){
            foreach ( $fields as $field_key => $field ){
                if ( $field["type"] === "key_select" || $field["type"] === "multi_select" ){
                    foreach ( $field["default"] as $option_key => $option_value ){
                        if ( isset( $option_value["deleted"] ) && $option_value["deleted"] == true ){
                            unset( $fields[$field_key]["default"][$option_key] );
                        }
                    }
                }
            }
        }

        $fields = apply_filters( 'dt_custom_fields_settings_after_combine', $fields, "contacts" );

        wp_cache_set( "contact_field_settings" . $cache_with_deleted, $fields );
        return $fields;
    } // End get_custom_fields_settings()

    public function get_post_type_settings_hook( $settings, $post_type ){
        if ( $post_type === "contacts" ){
            $fields = $this->get_custom_fields_settings();
            $settings = [
                'sources' => $this->get_custom_fields_settings( false, null, true )["sources"]["default"],
                'fields' => $fields,
                'address_types' => dt_get_option( "dt_site_custom_lists" )["contact_address_types"],
                'channels' => $this->get_channels_list(),
                'connection_types' => array_keys( array_filter( $fields, function ( $a ) {
                    return $a["type"] === "connection";
                } ) ),
                'label_singular' => $this->singular,
                'label_plural' => $this->plural,
                'post_type' => 'contacts'
            ];
        }
        return $settings;
    }

    /**
     * Field: Contact Fields
     *
     * @return array
     */
    public function contact_fields( int $post_id ) {
        global $wpdb, $post;

        $fields = [];
        $current_fields = [];

        $id = $post->ID ?? $post_id;
        if ( isset( $post->ID ) || isset( $post_id ) ) {
            $current_fields = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT
                        meta_key
                    FROM
                        `$wpdb->postmeta`
                    WHERE
                        post_id = %d
                        AND meta_key LIKE %s
                    ORDER BY
                        meta_key DESC",
                    $id,
                    $wpdb->esc_like( 'contact_' ) . '%'
                ),
                ARRAY_A
            );
        }

        foreach ( $current_fields as $value ) {
            $names = explode( '_', $value['meta_key'] );
            $tag = null;

            if ( strpos( $value["meta_key"], "_details" ) == false ) {
                $details = get_post_meta( $id, $value['meta_key'] . "_details", true );
                if ( $details && isset( $details["type"] ) ) {
                    if ( $names[1] != $details["type"] ) {
                        $tag = ' (' . ucwords( $details["type"] ) . ')';
                    }
                }
                $fields[ $value['meta_key'] ] = [
                    'name' => ucwords( $names[1] ) . $tag,
                    'tag'  => $names[1],
                ];
            }
        }

        return $fields;
    }


    /**
     * Helper function to create the unique metakey for contacts channels.
     *
     * @param $channel_key
     * @param $field_type
     *
     * @return string
     */
    public function create_channel_metakey( $channel_key, $field_type ) {
        return $field_type . '_' . $channel_key . '_' . $this->unique_hash(); // build key
    }

    /**
     * Create a unique hash for the key.
     *
     * @return bool|string
     */
    public function unique_hash() {
        return substr( md5( rand( 10000, 100000 ) ), 0, 3 ); // create a unique 3 digit key
    }

    /**
     * Get a list of the contact channels and their types
     *
     * @access public
     * @since  0.1.0
     * @return mixed
     */
    public function get_channels_list() {
        $channel_list = [
            "phone"     => [
                "label" => _x( 'Phone', 'field label', 'disciple_tools' ),
                "types" => [],
                "description" => _x( "A phone number for this contact.", 'contact information description', 'disciple_tools' )
            ],
            "email"     => [
                "label" => _x( 'Email', 'field label', 'disciple_tools' ),
                "types" => [],
                "description" => _x( "An email address for this contact", 'contact information description', 'disciple_tools' )
            ],
            "address" => [
                "label" => _x( "Address", 'field label', 'disciple_tools' ),
                "types" => dt_get_option( "dt_site_custom_lists" )["contact_address_types"],
                "description" => _x( "A physical address for this contact", 'contact information description', 'disciple_tools' )
            ],
            "facebook"  => [
                "label" => __( 'Facebook', 'disciple_tools' ),
                "types" => [
                    "facebook" => [
                        "label" => __( 'Facebook', 'disciple_tools' ),
                    ],
                ],
                "icon" => get_template_directory_uri() . "/dt-assets/images/facebook.svg",
                "hide_domain" => true
            ],
            "twitter"   => [
                "label" => __( 'Twitter', 'disciple_tools' ),
                "types" => [],
                "icon" => get_template_directory_uri() . "/dt-assets/images/twitter.svg",
                "hide_domain" => true
            ],
            "other"     => [
                "label" => _x( 'Other', 'field label', 'disciple_tools' ),
                "types" => [],
            ],
        ];

        $custom_channels = dt_get_option( "dt_custom_channels" );
        foreach ( $custom_channels as $custom_key => $custom_value ){
            $channel_list[$custom_key] = array_merge( $channel_list[$custom_key] ?? [], $custom_value );
        }
        return apply_filters( 'dt_custom_channels', $channel_list );
    }



    /**
     * Run on activation.
     *
     * @access public
     * @since  0.1.0
     */
    public function activation() {
        $this->flush_rewrite_rules();
    } // End activation()

    /**
     * Flush the rewrite rules
     *
     * @access public
     * @since  0.1.0
     */
    private function flush_rewrite_rules() {
        $this->register_post_type();
        flush_rewrite_rules();
    } // End flush_rewrite_rules()

    /**
     * @param $post_link
     * @param $post
     *
     * @return string
     */
    public function contacts_permalink( $post_link, $post ) {
        if ( $post->post_type === "contacts" ) {
            return home_url( "contacts/" . $post->ID . '/' );
        } else {
            return $post_link;
        }
    }

    public function contacts_rewrites_init() {
        add_rewrite_rule( 'contacts/([0-9]+)?$', 'index.php?post_type=contacts&p=$matches[1]', 'top' );
    }


} // End Class
