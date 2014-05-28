<?php

namespace Protec\BlooperReel\Recorder;

/**
 * DummyRecorder
 *
 * @package   Protec\BlooperReel\Recorder
 * @author    Protec Innovations <support@protecinnovations.co.uk>
 * @copyright 2014 Protec Innovations
 */
class DummyRecorder implements RecorderInterface
{
    /**
     * save
     *
     * @param string $identifier
     * @param string $data
     * @return string
     */
    public function save($identifier, $data)
    {
        return "An error occurred during execution; please try again later.";
    }
}
