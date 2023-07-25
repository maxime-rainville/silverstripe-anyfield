/* global jest, test, expect */
import React from 'react';
import { render } from '@testing-library/react';
import { stringifyData } from '../linkFieldHOC';

describe('stringifyData', () => {
  test('Entwine form field bootstrap', () => {
    const mock = jest.fn();
    const FakeComponent = (props) => {
      mock(props);
      return <div />;
    };
    const FakeHOC = stringifyData(FakeComponent);
    const props = {
      value: { foo: 'bar' },
      otherProp: 'baz'
    };

    render(<FakeHOC {...props} />);

    expect(mock).toHaveBeenCalledWith({
      dataStr: '{"foo":"bar"}',
      data: props.value,
      otherProp: props.otherProp
    });
  });

  test('Redux form bootstrap', () => {
    const mock = jest.fn();
    const FakeComponent = (props) => {
      mock(props);
      return <div />;
    };
    const FakeHOC = stringifyData(FakeComponent);
    const props = {
      data: [],
      value: JSON.stringify({ foo: 'bar' }),
      otherProp: 'baz'
    };

    render(<FakeHOC {...props} />);

    expect(mock).toHaveBeenCalledWith({
      dataStr: '{"foo":"bar"}',
      data: { foo: 'bar' },
      otherProp: props.otherProp
    });
  });
});
