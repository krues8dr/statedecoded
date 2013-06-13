<?php

/**
 * Default comes first
 */
Router::addRoute('.*', array('ContentController', 'notFound'));

/**
 * Specific routes next
 */
// Main Index
Router::addRoute('^/$', array('ContentController', 'index'));

// About page.
Router::addRoute('^/about/$', array('ContentController', 'about'));

// New activation
Router::addRoute('^/api-key/activate/(?P<secret>.*)', array('ApiKeyController', 'activateKey'));
// Old activation
Router::addRoute('^/api-key/\?secret=(?P<secret>.*)', array('ApiKeyController', 'activateKey'));
// Create an API Key
Router::addRoute('^/api-key/$', array('ApiKeyController', 'requestKey'));

/**
 * Dynamic routes last, most specific to least specific
 */
