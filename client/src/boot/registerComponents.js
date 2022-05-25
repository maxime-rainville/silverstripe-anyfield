/* eslint-disable */
import Injector from 'lib/Injector';
import LinkPicker from 'components/LinkPicker/LinkPicker';
import MultiLinkPicker from 'components/MultiLinkPicker/MultiLinkPicker';
import LinkField from 'components/LinkField/LinkField';
import MultiLinkField from 'components/MultiLinkField/MultiLinkField';
import LinkModal from 'components/LinkModal/LinkModal';
import FileLinkModal from 'components/LinkModal/FileLinkModal';


const registerComponents = () => {
  Injector.component.registerMany({
    LinkPicker,
    LinkField,
    MultiLinkPicker,
    MultiLinkField,
    'LinkModal.FormBuilderModal': LinkModal,
    'LinkModal.InsertMediaModal': FileLinkModal
  });
};

export default registerComponents;
