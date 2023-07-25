/* eslint-disable */
import PropTypes from 'prop-types';

const AllowedDataObjectClass = PropTypes.shape({
  key: PropTypes.string.isRequired,
  icon: PropTypes.string,
  title: PropTypes.string.isRequired,
  modalHandler: PropTypes.string,
});

export default AllowedDataObjectClass;
