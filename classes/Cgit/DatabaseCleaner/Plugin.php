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
     * Schedule name
     *
     * @var string
     */
    private $schedule = 'cgit_clean_database_schedule';

    /**
     * Schedule interval (s)
     *
     * @var integer
     */
    private $interval = 86400;

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
        register_activation_hook($file, [$this, 'schedule']);
        register_deactivation_hook($file, [$this, 'unschedule']);

        add_action($this->action, [$this, 'clean']);
        add_filter('cron_schedules', [$this, 'appendScheduleInterval']);
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

        wp_schedule_event(time(), $this->schedule, $this->action);
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
            if ($cleaner[0] !== '\\') {
                $cleaner = '\\Cgit\\DatabaseCleaner\\' . $cleaner;
            }

            (new $cleaner)->clean();
        }
    }

    /**
     * Append schedule interval to list of intervals
     *
     * @param array $schedules
     * @return array
     */
    public function appendScheduleInterval($schedules)
    {
        $schedules[$this->schedule] = [
            'interval' => $this->interval,
            'display' => 'database cleaner interval',
        ];

        return $schedules;
    }
}
