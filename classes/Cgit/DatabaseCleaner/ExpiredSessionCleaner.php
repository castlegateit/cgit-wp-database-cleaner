<?php

namespace Cgit\DatabaseCleaner;

class ExpiredSessionCleaner extends DatabaseCleaner
{
    /**
     * Clean expired sessions in the options table
     *
     * @return void
     */
    public function clean()
    {
        $table = $this->wpdb->options;

        // Identify all options that correspond to expired sessions, including
        // _wp_session_* and _wp_session_expired_* options.
        $options = $this->wpdb->get_col("SELECT opt.option_id
            FROM $options AS opt
            LEFT JOIN $options AS opt2
                ON SUBSTR(opt.option_name, 21) = SUBSTR(opt2.option_name, 13)
            LEFT JOIN $options AS opt3
                ON SUBSTR(opt.option_name, 13) = SUBSTR(opt3.option_name, 21)
            WHERE opt.option_name LIKE '_wp_session_%'
                AND (SUBSTR(opt.option_name, 21) = SUBSTR(opt2.option_name, 13)
                    OR SUBSTR(opt.option_name, 13) = SUBSTR(opt3.option_name, 21))
                AND FROM_UNIXTIME(opt.option_value) < NOW()");

        // No expired sessions?
        if (!$options) {
            return;
        }

        // Delete all expired sessions
        $options_sql = implode($options, ', ');

        $this->wpdb->query("DELETE FROM $table
            WHERE option_id IN ($options_sql)");
    }
}
