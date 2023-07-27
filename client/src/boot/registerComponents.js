/* eslint-disable */
import Injector from 'lib/Injector';
import AnyPicker from 'components/AnyPicker/AnyPicker';
import ManyAnyPicker from 'components/ManyAnyPicker/ManyAnyPicker';
import AnyField from 'components/AnyField/AnyField';
import ManyAnyField from 'components/ManyAnyField/ManyAnyField';
import AnyModal from 'components/AnyModal/AnyModal';
import FileAnyModal from 'components/AnyModal/FileAnyModal';


const registerComponents = () => {
  Injector.component.registerMany({
    AnyPicker,
    AnyField,
    ManyAnyPicker,
    ManyAnyField,
    'AnyModal.FormBuilderModal': AnyModal,
    'AnyModal.InsertMediaModal': FileAnyModal
  });
};

export default registerComponents;
