<?php

/**
 * Resume - HNG Internship Cohort 8 Stage 2 Task.
 *
 * @author Olayemi Olatayo <olatayo.olayemi.peter@gmail.com>
 */

// fetch bootstrap
require_once('bootstrap.php');

try {
    // render page
    renderPage('index');

} catch (Exception $exception) {
    displayError('System Error: ' .$exception->getMessage());
}

