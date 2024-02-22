<?php

namespace _PhpScoper9a3678ae6a12\Carbon_Fields\Datastore;

use _PhpScoper9a3678ae6a12\Carbon_Fields\Field\Field;
/**
 * Comment meta datastore class.
 * @internal
 */
class Comment_Meta_Datastore extends Meta_Datastore
{
    /**
     * Retrieve the type of meta data.
     *
     * @return string
     */
    public function get_meta_type()
    {
        return 'comment';
    }
    /**
     * Retrieve the meta table name to query.
     *
     * @return string
     */
    public function get_table_name()
    {
        global $wpdb;
        return $wpdb->commentmeta;
    }
    /**
     * Retrieve the meta table field name to query by.
     *
     * @return string
     */
    public function get_table_field_name()
    {
        return 'comment_id';
    }
}
