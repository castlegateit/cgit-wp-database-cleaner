<?php

namespace Cgit\DatabaseCleaner;

class Plugin
{
    /**
     * Action name
     *
     * @var string
     */
    private $action = 'cgit_clean_database';

    /**
     * Classes extended from the DatabaseCleaner class
     *
     * @var array
     */
    private $cleaners = [
        'ExpiredSessionCleaner',
    ];

    /**
     * Construct
     *
     * @param string $file
     * @return void
     */
    public function __construct($file = null)
    {
        $this->schedule();

        add_action($this->action, [$this, 'clean']);
        register_deactivation_hook($file, [$this, 'unschedule']);
    }

    /**
     * Add scheduled clean task
     *
     * @return void
     */
    public function schedule()
    {
        if (wp_next_scheduled($this->action)) {
            return;
        }

        wp_schedule_event(time(), 'daily', $this->action);
    }

    /**
     * Remove scheduled clean task
     *
     * @return void
     */
    public function unschedule()
    {
        wp_unschedule_event(wp_next_scheduled($this->action), $this->action);
    }

    /**
     * Clean stuff now
     *
     * Loop over the list of cleaner classes, instantiate each one, and run its
     * clean method.
     *
     * @return void
     */
    public function clean()
    {
        foreach ($this->cleaners as $cleaner) {
            (new $cleaner)->clean();
        }
    }
}
