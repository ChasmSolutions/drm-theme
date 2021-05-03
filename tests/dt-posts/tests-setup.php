<?php


add_filter( 'dt_details_additional_tiles', function ( $tiles, $post_type ){
    $tiles["a_beautiful_tile"] = [
        "label" => "A beautiful tile"
    ];
    return $tiles;
}, 10, 2 );

add_filter( 'dt_custom_fields_settings', function( $fields, $post_type ){
    //@todo connection

    $fields['number_test'] = [
        'name'        => __( 'Number field', 'disciple_tools' ),
        'type'        => 'number',
        'default'     => 0,
        'tile'      => 'a_beautiful_tile',
    ];
    $fields['text_test'] = [
        'name'        => __( 'Text', 'disciple_tools' ),
        'type'        => 'text',
        'default'     => 0,
        'tile'      => 'a_beautiful_tile',
    ];

    //@todo does not work without the 'contact_' prefix
    //$fields['communication_channel_test1'] = [
    //    'name'        => __( 'Communication Channel', 'disciple_tools' ),
    //    'type'        => 'communication_channel',
    //    'default'     => 0,
    //    'tile'      => 'a_beautiful_tile',
    //];
    $fields['contact_communication_channel_test'] = [
        'name'        => __( 'Communication Channel', 'disciple_tools' ),
        'type'        => 'communication_channel',
        'default'     => 0,
        'tile'      => 'a_beautiful_tile',
    ];

    $fields['user_select_test'] = [
        'name'        => __( 'User Select', 'disciple_tools' ),
        'type'        => 'user_select',
        'tile'        => 'a_beautiful_tile'
    ];
    $fields['array_test'] = [
        'name'        => __( 'Array', 'disciple_tools' ),
        'type'        => 'array',
        'tile'        => 'a_beautiful_tile'
    ];
    $fields["location_test"] = [
        'name' => "location field",
        'type' => 'location',
        'tile' => 'a_beautiful_tile'
    ];
    $fields['date_test'] = [
        'name'        => __( ' Date Field', 'disciple_tools' ),
        'description' => '',
        'type'        => 'date',
        'default'     => '',
        'tile' => 'a_beautiful_tile'
    ];
    $fields['boolean_test'] = [
        'name'        => __( 'Boolean', 'disciple_tools' ),
        'type'        => 'boolean',
        'default'     => false,
    ];
    $fields["multi_select_test"] = [
        'name' => "Random Options",
        'type' => "multi_select",
        "default" => [
            "one" => [ "label" => "option 1" ],
            "two" => [ "label" => "option 2" ],
            "three" => [ "label" => "option 3" ],
        ],
        "tile" => "a_beautiful_tile",
    ];
    $fields["key_select_test"] = [
        'name' => "Random Options",
        'type' => "key_select",
        "default" => [
            "one" => [ "label" => "option 1" ],
            "two" => [ "label" => "option 2" ],
            "three" => [ "label" => "option 3" ],
        ],
        "tile" => "a_beautiful_tile",
    ];
    $fields["tags_test"] = [
        'name' => "Random Options",
        'type' => "tags",
        "default" => [
            "one" => [ "label" => "option 1" ],
            "two" => [ "label" => "option 2" ],
            "three" => [ "label" => "option 3" ],
        ],
        "tile" => "a_beautiful_tile",
    ];
    return $fields;
}, 10, 2 );


function dt_test_get_sample_record_fields(){
    return [
        'title' => 'Custom Record',
        'number_test' => 101,
        'text_test' => "Some Random Text",
        'contact_communication_channel_test' => [ "values" => [ [ "value" => "haha, you would love to contact me" ] ] ],
//        'communication_channel_test1' => [ "values" => [ [ "value" => "haha, you would love to contact me" ] ] ],
        'user_select_test' => "1",
        'array_test' => [ 'test' => "test array", 'key' => "some funny key" ],
        'location_test' => [ "values" => [ [ "value" => '100089589' ] ] ],
        'date_test' => "2018-12-31",
        'boolean_test' => true,
        'multi_select_test' => [ "values" => [ [ "value" => 'one' ], [ "value" => "two" ] ] ],
        'key_select_test' => 'two',
        'tags_test' => [ "values" => [ [ "value" => "tag1" ], [ "value" => "tagToDelete" ] ] ],
    ];
}
