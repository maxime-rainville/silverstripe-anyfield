import React from 'react';
import FormBuilderModal from 'components/FormBuilderModal/FormBuilderModal';
import url from 'url';
import qs from 'qs';
import Config from 'lib/Config';

const leftAndMain = 'SilverStripe\\Admin\\LeftAndMain';

const buildSchemaUrl = (key, data) => {
  const { schemaUrl } = Config.getSection(leftAndMain).form.DynamicLink;

  const parsedURL = url.parse(schemaUrl);
  const parsedQs = qs.parse(parsedURL.query);
  parsedQs.key = key;
  if (data) {
    parsedQs.data = JSON.stringify(data);
  }
  return url.format({ ...parsedURL, search: qs.stringify(parsedQs) });
};

const LinkModal = ({ type, editing, data, ...props }) => {
  if (!type) {
    return false;
  }

  return (
    <FormBuilderModal
      title={type.title}
      isOpen={editing}
      schemaUrl={buildSchemaUrl(type.key, data)}
      identifier="Link.EditingLinkInfo"
      {...props}
    />
  );
};

export default LinkModal;
