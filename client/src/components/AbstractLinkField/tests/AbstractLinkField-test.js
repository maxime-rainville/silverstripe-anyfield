/* global jest, test, expect */
import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import LinkField from '../AbstractLinkField';
import { loadComponent } from 'lib/Injector';

const props = {
  id: 'my-link-field',
  loading: false,
  Loading: () => <div>Loading...</div>,
  data: {
    Root: null,
    Main: null,
    Title: '',
    OpenInNew: 0,
    ExternalUrl: 'http://google.com',
    ID: null,
    typeKey: 'external'
  },
  Picker: ({ id, onEdit, types, onClear, onSelect }) => <div>
    <span>Picker</span>
    <span>fieldid:{id}</span>
    <span>types:{types[0].key}-{types[0].icon}-{types[0].title}</span>
    <button onClick={() => onEdit(123)}>onEdit</button>
    <button onClick={(event) => onClear(event, 123)}>onClear</button>
    <button onClick={(event) => onSelect('sitetree')}>onSelect</button>
    </div>,
  onChange: jest.fn(),
  types: {
    sitetree: {
      key: 'sitetree',
      icon: 'page',
      title: 'Site tree'
    }
  },
  linkDescriptions: [
    { title: 'link title', description: 'link description' }
  ],
  clearLinkData: jest.fn(),
  buildLinkProps: jest.fn(),
  updateLinkData: jest.fn(),
  selectLinkData: jest.fn(),
};


const LinkModal = () => <div>LinkModal</div>;
jest.mock('lib/Injector', () => ({
    loadComponent: () => LinkModal
  })
);


describe('AbstractLinkField', () => {
  test('Loading component', () => {
    render(<LinkField {...props} loading />);
    expect(screen.getByText('Loading...')).toBeInTheDocument();
  });

  test('Empty field', () => {
    render(<LinkField {...props} data={{ }} />);
    expect(screen.getByText('Picker')).toBeInTheDocument();
    expect(screen.getByText('fieldid:my-link-field')).toBeInTheDocument();
    expect(screen.getByText('types:sitetree-page-Site tree')).toBeInTheDocument();
  });
});


