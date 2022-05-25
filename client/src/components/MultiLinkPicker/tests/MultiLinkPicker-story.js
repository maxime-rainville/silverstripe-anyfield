import React from 'react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { storiesOf } from '@storybook/react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { action } from '@storybook/addon-actions';
import MultiLinkPicker from '../MultiLinkPicker';

const types = [
  { key: 'cms', title: 'Page on this site', icon: 'page' },
  { key: 'asset', title: 'File', icon: 'menu-files' },
  { key: 'external', title: 'External URL', icon: 'external-link' },
  { key: 'mailto', title: 'Email address', icon: 'block-email' },
];

const links = [
  {
    id: '1',
    title: 'Our people',
    type: types[0],
    description: '/about-us/people'
  },
  {
    id: '2',
    title: 'About us',
    type: types[0],
    description: '/about-us'
  },
  {
    id: '3',
    title: 'My document',
    type: types[1],
    description: '/my-document.pdf'
  },
  {
    id: '4',
    title: 'Silverstripe',
    type: types[2],
    description: 'https://www.silverstripe.com/'
  },
  {
    id: '5',
    title: 'john@example.com',
    type: types[3],
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
  types,
  onSelect,
  onClear,
  onEdit,
  links
};

storiesOf('LinkField/MultiLinkPicker', module)
  .add('MultiLinkPicker', () => (
    <MultiLinkPicker {...props} />
  ));
