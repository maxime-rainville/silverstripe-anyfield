import PropTypes from 'prop-types';

const AnyFieldData = PropTypes.shape({
  typeKey: PropTypes.string,
  Title: PropTypes.string,
  OpenInNew: PropTypes.bool,
});

export default AnyFieldData;
