<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 */

namespace App\Support\Contracts;

/**
 * interface contains constants the describe what type of server error was produced in
 * the application itself this should be return in the response of each api resource
 * in order for the client to detect what server error was received
 */
interface ServerErrorType
{
    /**
     * type when creation of new user was failed due to server error
     *
     * @const
     */
    const SERVER_ERROR_FAILED_USER_CREATE = 'user_created_failed';

    /**
     * type when updating of user was failed due to server error
     *
     * @const
     */
    const SERVER_ERROR_FAILED_USER_UPDATE = 'user_updated_failed';

    /**
     * type when deleting of user was failed due to server error
     *
     * @const
     */
    const SERVER_ERROR_FAILED_DELETE_USER = 'user_delete_failed';

    /**
     * type when counting of user was failed due to server error
     *
     * @const
     */
    const SERVER_ERROR_FAILED_COUNT_USER = 'user_count_failed';

    const SERVER_ERROR_FAILED_FETCH_USER = 'user_fetch_failed';

    /**
     * type when email has a conflict to the server
     *
     * @const
     */
    const CLIENT_ERROR_CONFLICT_EMAIL = 'email_conflict';

    /**
     * type when client request version was invalid
     *
     * @const
     */
    const CLIENT_ERROR_INVALID_VERSION = 'invalid_version';

    /**
     * type when contact number request version was invalid
     *
     * @const
     */
    const CLIENT_ERROR_INVALID_CONTACT_NUMBER = 'invalid_contact_number';
}
