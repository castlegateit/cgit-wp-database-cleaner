<?php

namespace Cgit\DatabaseCleaner;

abstract class DatabaseCleaner
{
    /**
     * WordPress database instance
     *
     * @var wpdb
     */
    protected $wpdb;

    /**
     * Construct
     *
     * @return void
     */
    final public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->init();
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function init()
    {
        // ...
    }

    /**
     * Clean stuff
     *
     * @return void
     */
    abstract public function clean();
}
