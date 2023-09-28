import React from 'react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { storiesOf } from '@storybook/react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { action } from '@storybook/addon-actions';
import ManyAnyPicker from '../ManyAnyPicker';

const allowedDataObjectClasses = [
  {
    key: 'SilverStripe\\LinkField\\Models\\EmailLink',
    title: 'Email Link',
    icon: 'p-mail',
    modalHandler: null
  },
  {
    key: 'SilverStripe\\LinkField\\Models\\ExternalLink',
    title: 'External Link',
    icon: 'external-link',
    modalHandler: null
  },
  {
    key: 'SilverStripe\\LinkField\\Models\\FileLink',
    title: 'File Link',
    icon: 'menu-files',
    modalHandler: 'InsertMediaModal'
  },
  {
    key: 'SilverStripe\\LinkField\\Models\\PhoneLink',
    title: 'Phone Link',
    icon: 'link',
    modalHandler: null
  },
  {
    key: 'SilverStripe\\LinkField\\Models\\SiteTreeLink',
    title: 'Site Tree Link',
    icon: 'page',
    modalHandler: null
  }
];

const dataobjects = [
  {
    id: '1',
    title: 'Our people',
    dataObjectClass: allowedDataObjectClasses[0],
    description: '/about-us/people'
  },
  {
    id: '2',
    title: 'About us',
    dataObjectClass: allowedDataObjectClasses[0],
    description: '/about-us'
  },
  {
    id: '3',
    title: 'My document',
    dataObjectClass: allowedDataObjectClasses[1],
    description: '/my-document.pdf'
  },
  {
    id: '4',
    title: 'Silverstripe',
    dataObjectClass: allowedDataObjectClasses[2],
    description: 'https://www.silverstripe.com/'
  },
  {
    id: '5',
    title: 'john@example.com',
    dataObjectClass: allowedDataObjectClasses[3],
    description: 'john@example.com'
  },
];

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
  dataobjects,
  baseDataObjectName: 'Link',
  id: 'Form_EditForm_MyTestLink',
  sortable: true,
};

storiesOf('AnyField/ManyAnyPicker', module)
  .add('ManyAnyPicker', () => (
    <ManyAnyPicker {...props} />
  ));
