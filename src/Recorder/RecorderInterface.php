<?php

namespace Protec\BlooperReel\Recorder;

/**
 * RecorderInterface
 *
 * @package   Protec\BlooperReel\Recorder
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2014 Protec Innovations
 */
interface RecorderInterface
{
    /**
     * save
     *
     * @param string $identifier
     * @param string $data
     * @return $this
     */
    public function save($identifier, $data);
}
