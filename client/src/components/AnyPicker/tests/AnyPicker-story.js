/* eslint-disable */
import React from 'react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { storiesOf } from '@storybook/react';
// eslint-disable-next-line import/no-extraneous-dependencies
import { action } from '@storybook/addon-actions';
import AnyPicker from '../AnyPicker';

const types = [
  { key: 'cms', title: 'Page on this site' },
  { key: 'asset', title: 'File' },
  { key: 'external', title: 'External URL' },
  { key: 'mailto', title: 'Email address' },
];

const dataobject = {
  title: 'Our people',
  type: types[0],
  description: '/about-us/people'
};

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
  onEdit
};

storiesOf('AnyField/AnyPicker', module)
  .add('Initial', () => (
    <AnyPicker {...props} />
  ))
  .add('Selected', () => (
    <AnyPicker {...props} dataobject={dataobject} />
  ));
