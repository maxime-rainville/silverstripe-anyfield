import PropTypes from 'prop-types';

const LinkData = PropTypes.shape({
  typeKey: PropTypes.string,
  Title: PropTypes.string,
  OpenInNew: PropTypes.bool,
});

export default LinkData;
