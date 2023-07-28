import PropTypes from 'prop-types';

const AnyFieldData = PropTypes.shape({
  dataObjectClassKey: PropTypes.string,
  Title: PropTypes.string,
  ID: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
});

export default AnyFieldData;
