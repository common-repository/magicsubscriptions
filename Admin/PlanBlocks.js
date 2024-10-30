/**
 * MagicSubscriptions WordPress Plugin
 * Copyright (C) 2019 Ronnie Barker
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 **/




wp.blocks.updateCategory('magic-subscriptions', {icon: MagicSubscriptions_WizardIcon});


wp.blocks.registerBlockType('magic-subscriptions/plans', {
    title: 'Plans',
    icon: MagicSubscriptions_SquareIcon,
    category: 'magic-subscriptions',
    attributes: {
        code: {type: 'string'}
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */

    edit: function (props) {
        function updateCode(event) {
            props.setAttributes({code: event.target.value})
        }

        return wp.element.createElement('div', {},
            wp.element.createElement("h3", {}, "Magic Subscription Plans"),
            wp.element.createElement("label", {}, "code: "),
            wp.element.createElement("input", {type: "text", value: props.attributes.code, onChange: updateCode})
        );
    },
    save: function (props) {
        return null;
        // Rendering in PHP
    }
});


wp.blocks.registerBlockType('magic-subscriptions/connect', {
    title: 'Connect',
    icon: MagicSubscriptions_WifiIcon,
    category: 'magic-subscriptions',

    /* This configures how the content and color fields will work, and sets up the necessary elements */

    edit: function (props) {
        return wp.element.createElement("div", {},
            wp.element.createElement("h3", {}, "Magic Subscriptions Connect Button")
        );
    },
    save: function (props) {
        return null;
        // Rendering in PHP
    }
});


wp.blocks.registerBlockType('magic-subscriptions/summary', {
    title: 'Summary',
    icon: MagicSubscriptions_ClipboardIcon,
    category: 'magic-subscriptions',

    /* This configures how the content and color fields will work, and sets up the necessary elements */

    edit: function (props) {
        return wp.element.createElement("div", {},
            wp.element.createElement("h3", {}, "Magic Subscriptions Summary")
        );
    },
    save: function (props) {
        return null;
        // Rendering in PHP
    }
})


wp.blocks.registerBlockType('magic-subscriptions/widesummary', {
    title: 'Wide Summary',
    icon: MagicSubscriptions_WideClipboardIcon,
    category: 'magic-subscriptions',

    /* This configures how the content and color fields will work, and sets up the necessary elements */

    edit: function (props) {
        return wp.element.createElement("div", {},
            wp.element.createElement("h3", {}, "Magic Subscriptions Wide Summary")
        );
    },
    save: function (props) {
        return null;
        // Rendering in PHP
    }
});


wp.blocks.registerBlockType('magic-subscriptions/shop', {
    title: 'Shop',
    icon: MagicSubscriptions_BagIcon,
    category: 'magic-subscriptions',
    attributes: {
        tag: {type: 'string'},
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */

    edit: function (props) {
        function updateTag(event) {
            props.setAttributes({tag: event.target.value})
        }

        return wp.element.createElement("div", {},
            wp.element.createElement("h3", {}, "Magic Subscriptions Shop"),
            wp.element.createElement("label", {}, "tag: "),
            wp.element.createElement("input", {type: "text", value: props.attributes.tag, onChange: updateTag})
        );
    },
    save: function (props) {
        return null;
        // Rendering in PHP
    }
});
