<?php namespace Viper\Controllers;
/**
 * User Base Controller
 * 
 * @author Ollie Read <labs@ollieread.com>
 * @version 1.0-alpha
 * @license http://opensource.org/licenses/MIT MIT
 */

use Viper\Models as Models;
use Viper\Core\Facades\ViperAuth as Auth;

/**
 * Providing base functionality for the user related endpoints.
 */
class User_BaseController extends BaseController
{
    /**
     * Helper function for the simplification of logging a user in. This is
     * called once we've done the manual verification, this is just simply for
     * creating and assigning a new user token.
     *
     * @return \Viper\Models\User_Token
     */
    protected function _login()
    {
        /**
         * We've already got the user for this request, now we just need to set
         * a token and respond so they're logged in for future requests.
         */
        if ($this->isValidUser()) {
            $token = new Models\User_Token;
            $token->generate();

            if (Auth::user()->token()->save($token)) {
                return $token;
            }
        }
        /**
         * We return null because this method should return an instance of User_Token,
         * and false would be boolean. Blame this on the fact I've spent a week
         * writing Java, and as you may know, Java is a bit of dick in regards
         * to return types.
         */
        return null;
    }

    /**
     * Helper function for the simplification of logging a user out. Also
     * serves as a dual purpose, with a single way to remove a user token.
     *
     * @return bool
     */
    protected function _logout()
    {
        if ($this->isValidUser()) {
            /**
             * Grab the active token. We do this just incase there is another
             * token floating around.
             */
            $active_token = Auth::user()->token;
            /**
             * Now remove that token and return success. Seeing as this is a
             * simple method/endpoint, no data is required on the return.
             */
            if ($active_token) {
                $active_token->delete();

                return true;
            }
            /**
             * Since this is a helper function, we want to avoid throwing exceptions,
             * and leave that up to the core code calling this.
             */
            return false;
        }
        /**
         * Like the above, we simply return a boolean response rather than throw an
         * exception.
         */
        return false;
    }

}