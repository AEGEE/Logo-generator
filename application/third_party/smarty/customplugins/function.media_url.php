<?php
/**
 *
 */
// ACHTUNG: move these to global config file
define( "UPLOAD_DIRECTORY", "uploads" );

function smarty_function_media_url( $params, &$smarty )
{
    if( !empty( $params['type'] ) )
    {
        if( $params['type'] == "gallery" )
        {
            if( !empty( $params['gallery'] ) && !empty( $params['resource'] ) )
            {
                $resource = $params['resource'];
                $gallery  = $params['gallery'];
                $location = UPLOAD_DIRECTORY . '/gallery' .  '/' . $resource['file_name'] . ($params['thumb'] ? '/t_' : '/') . $gallery['id'] . '.' . ($params['thumb'] ? $gallery['thumb_ext'] : $gallery['full_ext']);
            }
        }
        else if( $params['type'] == "avatar" )
        {
            if( !empty( $params['image'] ) )
            {
                $location = UPLOAD_DIRECTORY . '/avatar' .  '/' . $params['image'];
            }
        }
        else
        {
            return 'images/error.png';
        }

        if( !empty($location) && file_exists( BASEPATH . '../' . $location ) )
        {
            return base_url() . $location;
        }
        else
        {
            return 'images/default-' . $params['type'] . '.png';
        }
    }
}
