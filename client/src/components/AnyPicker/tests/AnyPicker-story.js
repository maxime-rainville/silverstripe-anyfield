/* eslint-disable */
import React from 'react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { storiesOf } from '@storybook/react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { action } from '@storybook/addon-actions';
import AnyPicker from '../AnyPicker';

const allowedDataObjectClasses = [
  {
    "key": "SilverStripe\\LinkField\\Models\\EmailLink",
    "title": "Email Link",
    "icon": "p-mail",
    "modalHandler": null
  },
  {
    "key": "SilverStripe\\LinkField\\Models\\ExternalLink",
    "title": "External Link",
    "icon": "external-link",
    "modalHandler": null
  },
  {
    "key": "SilverStripe\\LinkField\\Models\\FileLink",
    "title": "File Link",
    "icon": "menu-files",
    "modalHandler": "InsertMediaModal"
  },
  {
    "key": "SilverStripe\\LinkField\\Models\\PhoneLink",
    "title": "Phone Link",
    "icon": "link",
    "modalHandler": null
  },
  {
    "key": "SilverStripe\\LinkField\\Models\\SiteTreeLink",
    "title": "Site Tree Link",
    "icon": "page",
    "modalHandler": null
  }
]

const dataObjectClass = {
  "key": "SilverStripe\\LinkField\\Models\\EmailLink",
  "title": "Email Link",
  "icon": "p-mail",
  "modalHandler": null
};

const onSelect = action('onSelect');
onSelect.toString = () => 'onSelect';

const onEdit = action('onEdit');
onEdit.toString = () => 'onEdit';

const onClear = action('onClear');
onClear.toString = () => 'onClear';

const props = {
  allowedDataObjectClasses,
  onSelect,
  onClear,
  onEdit,
  baseDataObjectName: "Link",
  id: '"Form_EditForm_MyTestLink"'
};

storiesOf('AnyField/AnyPicker', module)
  .add('Initial', () => (
    <AnyPicker {...props} />
  ))
  .add('Selected', () => (
    <AnyPicker {...props} dataObjectClass={dataObjectClass} title="Contact us" description="hello@world.com" />
  ));
