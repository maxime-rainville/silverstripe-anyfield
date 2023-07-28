/* global jest, test, expect */
import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import AnyField from '../AbstractAnyField';
import { loadComponent } from 'lib/Injector';

const props = {
  id: 'my-any-field',
  loading: false,
  Loading: () => <div>Loading...</div>,
  data: {
    Root: null,
    Main: null,
    Title: '',
    OpenInNew: 0,
    ExternalUrl: 'http://google.com',
    ID: null,
    dataObjectClassKey: 'sitetree'
  },
  Picker: ({ id, onEdit, allowedDataObjectClasses, onClear, onSelect }) => <div>
    <span>Picker</span>
    <span>fieldid:{id}</span>
    <span>allowedDataObjectClasses:{allowedDataObjectClasses[0].key}-{allowedDataObjectClasses[0].icon}-{allowedDataObjectClasses[0].title}</span>
    <button onClick={() => onEdit(123)}>onEdit</button>
    <button onClick={(event) => onClear(event, 123)}>onClear</button>
    <button onClick={(event) => onSelect('sitetree')}>onSelect</button>
    </div>,
  onChange: jest.fn(),
  allowedDataObjectClasses: {
    sitetree: {
      key: 'sitetree',
      icon: 'page',
      title: 'Site tree',
      modalHandler: null
    }
  },
  anyDescriptions: [
    { title: 'Object title', description: 'Object description' }
  ],
  clearData: jest.fn(),
  buildProps: jest.fn(),
  updateData: jest.fn(),
  selectData: jest.fn(),
};


const AnyModal = () => <div>AnyModal</div>;
jest.mock('lib/Injector', () => ({
    loadComponent: () => AnyModal
  })
);


describe('AbstractAnyField', () => {
  test('Loading component', () => {
    render(<AnyField {...props} loading />);
    expect(screen.getByText('Loading...')).toBeInTheDocument();
  });

  test('Empty field', () => {
    render(<AnyField {...props} data={{ }} />);
    expect(screen.getByText('Picker')).toBeInTheDocument();
    expect(screen.getByText('fieldid:my-any-field')).toBeInTheDocument();
    expect(screen.getByText('allowedDataObjectClasses:sitetree-page-Site tree')).toBeInTheDocument();
  });
});


