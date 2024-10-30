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



wp.hooks.addFilter("blocks.registerBlockType", "magic-subscriptions/attributes", function (blockType) {
    if (blockType.attributes) {
        blockType.attributes = Object.assign(blockType.attributes, {
            MagicSubscriptionsAttributes: {
                type: "object",
                default: {visibleInState: ""}
            }
        });
    }
    return blockType;
});


wp.hooks.addFilter("editor.BlockEdit", "magic-subscriptions/attributes-inspector",
    wp.compose.createHigherOrderComponent(function (blockEdit) {
        const el = wp.element.createElement;
        return function (properties) {
            var msAttributes = properties.attributes.MagicSubscriptionsAttributes;
            return el(wp.element.Fragment, {},
                el(blockEdit, properties),
                el(wp.editor.InspectorControls, {},
                    el(wp.components.PanelBody,
                        {
                            title: "Magic Subscriptions",
                            icon: MagicSubscriptions_WizardIcon,
                            initialOpen: msAttributes.visibleInState != ""
                        },
                        el(wp.components.RadioControl, {
                            label: "Visible when:",
                            help: "Show this block only when the Magic Subscriptions connection is in a particular state",
                            selected: msAttributes.visibleInState || "",
                            options: [
                                {label: "Always", value: ""},
                                {label: "When connected", value: "connected"},
                                {label: "When NOT connected", value: "not-connected"}
                            ],
                            onChange: function (value) {
                                var x = Object.assign({}, msAttributes);
                                x.visibleInState = value;
                                properties.setAttributes({MagicSubscriptionsAttributes: x});
                            }
                        })
                    )
                )
            );
        }
    }));
