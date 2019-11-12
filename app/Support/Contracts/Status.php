<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Support\Contracts;

interface Status
{
    /**
     * active status value
     *
     * @const
     */
    const STATUS_ACTIVE = 'A';

    /**
     * pending status value
     *
     * @const
     */
    const STATUS_PENDING = 'P';

    /**
     * disabled status value
     *
     * @const
     */
    const STATUS_DISABLED = 'D';

    /**
     * hidden status value
     *
     * @const
     */
    const STATUS_HIDDEN = 'H';
}
