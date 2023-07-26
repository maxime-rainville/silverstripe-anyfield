import React from 'react';
import FormBuilderModal from 'components/FormBuilderModal/FormBuilderModal';
import url from 'url';
import qs from 'qs';
import Config from 'lib/Config';

const leftAndMain = 'SilverStripe\\Admin\\LeftAndMain';

const buildSchemaUrl = (key, data) => {
  const { schemaUrl } = Config.getSection(leftAndMain).form.AnyField;

  const parsedURL = url.parse(schemaUrl);
  const parsedQs = qs.parse(parsedURL.query);
  parsedQs.key = key;
  if (data) {
    parsedQs.data = JSON.stringify(data);
  }
  return url.format({ ...parsedURL, search: qs.stringify(parsedQs) });
};

const AnyModal = ({ dataObjectClass, editing, data, ...props }) => {
  if (!dataObjectClass) {
    return false;
  }

  return (
    <FormBuilderModal
      title={dataObjectClass.title}
      isOpen={editing}
      schemaUrl={buildSchemaUrl(dataObjectClass.key, data)}
      identifier="AnyModal.EditingDataObjectInfo"
      {...props}
    />
  );
};

export default AnyModal;
