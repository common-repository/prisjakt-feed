import './actions';
import './feedFilters';
import './categoryMapping';


// eslint-disable-next-line no-duplicate-imports
import {setupSelects, updateFields} from './feedFilters';
import {filterSelectsOptions, setupFieldMappingSelects} from './fieldMapping';

class PrisjaktFeedFeed {
    constructor() {
        this.form = document.querySelector(
            `form#${prisjakt_ajax_object.form_id}`
        );

        this.steps = prisjakt_ajax_object.steps;

        /*
         * Remove Wordpress escape page
         */
        this.disableFeedExitPrompt();

        /**
         * Filters mapping
         */
        setupSelects();
        updateFields();

        /**
         * Field mapping options filter
         */
        setupFieldMappingSelects();
    }

    disableFeedExitPrompt() {
        jQuery(window).unbind('beforeunload');

        window.autosave = function () {
            return false;
        };

        if (typeof window.wp.autosave === 'undefined') return;
        window.wp.autosave.server.postChanged = function () {
        };
    }
}

new PrisjaktFeedFeed();
