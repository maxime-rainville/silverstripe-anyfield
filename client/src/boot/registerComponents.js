/* eslint-disable */
import Injector from 'lib/Injector';
import AnyPicker from 'components/AnyPicker/AnyPicker';
import MultiAnyPicker from 'components/MultiAnyPicker/MultiAnyPicker';
import AnyField from 'components/AnyField/AnyField';
import MultiAnyField from 'components/MultiAnyField/MultiAnyField';
import AnyModal from 'components/AnyModal/AnyModal';
import FileAnyModal from 'components/AnyModal/FileAnyModal';


const registerComponents = () => {
  Injector.component.registerMany({
    AnyPicker,
    AnyField,
    MultiAnyPicker,
    MultiAnyField,
    'AnyModal.FormBuilderModal': AnyModal,
    'AnyModal.InsertMediaModal': FileAnyModal
  });
};

export default registerComponents;
