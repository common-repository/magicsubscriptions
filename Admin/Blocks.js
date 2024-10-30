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


const MagicSubscriptions_Orange = "#ef7738";


const MagicSubscriptions_logoIcon = wp.element.createElement('svg', {width: 24, height: 24},
    wp.element.createElement('rect', {
        x: "0.5",
        y: "0.5",
        width: "23",
        height: "23",
        fill: "#ef7738",
        stroke: "#3c3c3b",
        strokeLinecap: "round",
        strokeLinejoin: "round",
        strokeWidth: "1px"
    }),
    wp.element.createElement('path', {
        d: "M12.89,3.28V20.94H10.06V9L8.93,20.94h-2L5.74,9.29V20.94H2.91V3.28H7.09q.2,1.59.39,3.75l.46,4.5.74-8.25Z",
        fill: "#3c3c3b"
    }),
    wp.element.createElement('path', {
        d: "M21,8.62H18V7.31a3.38,3.38,0,0,0-.12-1.16.39.39,0,0,0-.38-.25.48.48,0,0,0-.45.34,2.6,2.6,0,0,0-.15,1,4,4,0,0,0,.17,1.33A3,3,0,0,0,18,9.67a10.84,10.84,0,0,1,2.72,3,9.33,9.33,0,0,1,.56,3.76A9.32,9.32,0,0,1,21,19.2a3,3,0,0,1-1.2,1.5,3.86,3.86,0,0,1-4.3-.09,3.2,3.2,0,0,1-1.2-1.78A12.69,12.69,0,0,1,14,15.77V14.61h3v2.15A3.77,3.77,0,0,0,17.15,18a.44.44,0,0,0,.45.28.52.52,0,0,0,.48-.36,2.9,2.9,0,0,0,.16-1.07,4.46,4.46,0,0,0-.3-2,11,11,0,0,0-1.52-1.6,19,19,0,0,1-1.6-1.65,4.18,4.18,0,0,1-.64-1.42,8.26,8.26,0,0,1-.26-2.31,9,9,0,0,1,.36-3,3,3,0,0,1,1.18-1.46,3.47,3.47,0,0,1,2-.53,3.84,3.84,0,0,1,2.15.58,2.73,2.73,0,0,1,1.17,1.45,10.82,10.82,0,0,1,.29,3Z",
        fill: "#3c3c3b"
    })
);


const MagicSubscriptions_SquareIcon = wp.element.createElement('i', {
    class: "fas fa-lg fa-vector-square",
    style: {color: MagicSubscriptions_Orange}
});


const MagicSubscriptions_WizardIcon = wp.element.createElement('span', {}, wp.element.createElement('i', {
    class: "fas fa-lg fa-hat-wizard",
    style: {color: MagicSubscriptions_Orange}
}));


const MagicSubscriptions_WifiIcon = wp.element.createElement('i', {
    class: "fas fa-lg fa-wifi",
    style: {color: MagicSubscriptions_Orange}
});


const MagicSubscriptions_ClipboardIcon = wp.element.createElement('i', {
    class: "far fa-lg fa-clipboard",
    style: {color: MagicSubscriptions_Orange}
});


const MagicSubscriptions_WideClipboardIcon = wp.element.createElement('i', {
    class: "far fa-lg fa-clipboard fa-rotate-270",
    style: {color: MagicSubscriptions_Orange}
});


const MagicSubscriptions_BagIcon = wp.element.createElement('i', {
    class: "fas fa-lg fa-shopping-bag",
    style: {color: MagicSubscriptions_Orange}
});
