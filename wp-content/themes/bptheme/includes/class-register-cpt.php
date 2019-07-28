<?php 

//https://code.tutsplus.com/articles/custom-post-type-helper-class--wp-25104

class Custom_Post_Type {

    public function __construct( $name, $args = array(), $labels = array() ) {
        // Set some important variables
        $this->post_type_name        = strtolower( str_replace( ' ', '_', $name ) );
        $this->post_type_args        = $args;
        $this->post_type_labels  = $labels;
        
        // Add action to register the post type, if the post type does not already exist
        if( ! post_type_exists( $this->post_type_name ) )
        {
            add_action( 'init', array( &$this, 'register_post_type' ) );
        }
        
        // Listen for the save post hook
        $this->save();
    }

    public function register_post_type() {

        //Capitalize the words and make it plural
        $name       = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
        $plural     = $name . 's';
        
        // We set the default labels based on the post type name and plural. We overwrite them with the given labels.
        $labels = array_merge(
        
            // Default
            array(
                'name'                  => _x( $plural, 'post type general name' ),
                'singular_name'         => _x( $name, 'post type singular name' ),
                'add_new'               => _x( 'Add New', strtolower( $name ) ),
                'add_new_item'          => __( 'Add New ' . $name ),
                'edit_item'             => __( 'Edit ' . $name ),
                'new_item'              => __( 'New ' . $name ),
                'all_items'             => __( 'All ' . $plural ),
                'view_item'             => __( 'View ' . $name ),
                'search_items'          => __( 'Search ' . $plural ),
                'not_found'             => __( 'No ' . strtolower( $plural ) . ' found'),
                'not_found_in_trash'    => __( 'No ' . strtolower( $plural ) . ' found in Trash'), 
                'parent_item_colon'     => '',
                'menu_name'             => $plural
            ),
            
            // Given labels
            $this->post_type_labels
            
        );
        
        // Same principle as the labels. We set some defaults and overwrite them with the given arguments.
        $args = array_merge(
        
            // Default
            array(
                'label'                 => $plural,
                'labels'                => $labels,
                'public'                => true,
                'show_ui'               => true,
                'supports'              => array( 'title', 'content', 'editor' ),
                'show_in_nav_menus'     => true,
                '_builtin'              => false,
            ),
            
            // Given args
            $this->post_type_args
            
        );
        
        // Register the post type
        register_post_type( $this->post_type_name, $args );
    }
}