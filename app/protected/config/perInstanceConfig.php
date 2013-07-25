<?php
    /**
     * Custom configuration for the Zurmo Zoo project.
     */

    $instanceConfig   = array(
        'modules' => array(
            'animals',
        ),
    );
    $instanceConfig['components']['custom']['class'] =
        'application.extensions.zurmozoo.components.ZurmoZooCustomManagement';
    $instanceConfig['import'][] = "application.extensions.zurmozoo.*";                          // Not Coding Standard
    $instanceConfig['import'][] = "application.extensions.zurmozoo.components.*";               // Not Coding Standard
    $instanceConfig['import'][] = "application.extensions.zurmozoo.utils.*";                    // Not Coding Standard
?>