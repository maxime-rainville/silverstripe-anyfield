/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./client/src/boot/index.js":
/*!**********************************!*\
  !*** ./client/src/boot/index.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __unused_webpack_exports, __webpack_require__) {



var _registerReducers = _interopRequireDefault(__webpack_require__(/*! ./registerReducers */ "./client/src/boot/registerReducers.js"));
var _registerComponents = _interopRequireDefault(__webpack_require__(/*! ./registerComponents */ "./client/src/boot/registerComponents.js"));
var _registerQueries = _interopRequireDefault(__webpack_require__(/*! ./registerQueries */ "./client/src/boot/registerQueries.js"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
document.addEventListener('DOMContentLoaded', () => {
  (0, _registerComponents.default)();
  (0, _registerQueries.default)();
  (0, _registerReducers.default)();
});

/***/ }),

/***/ "./client/src/boot/registerComponents.js":
/*!***********************************************!*\
  !*** ./client/src/boot/registerComponents.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _Injector = _interopRequireDefault(__webpack_require__(/*! lib/Injector */ "lib/Injector"));
var _LinkPicker = _interopRequireDefault(__webpack_require__(/*! components/LinkPicker/LinkPicker */ "./client/src/components/LinkPicker/LinkPicker.js"));
var _MultiLinkPicker = _interopRequireDefault(__webpack_require__(/*! components/MultiLinkPicker/MultiLinkPicker */ "./client/src/components/MultiLinkPicker/MultiLinkPicker.js"));
var _LinkField = _interopRequireDefault(__webpack_require__(/*! components/LinkField/LinkField */ "./client/src/components/LinkField/LinkField.js"));
var _MultiLinkField = _interopRequireDefault(__webpack_require__(/*! components/MultiLinkField/MultiLinkField */ "./client/src/components/MultiLinkField/MultiLinkField.js"));
var _LinkModal = _interopRequireDefault(__webpack_require__(/*! components/LinkModal/LinkModal */ "./client/src/components/LinkModal/LinkModal.js"));
var _FileLinkModal = _interopRequireDefault(__webpack_require__(/*! components/LinkModal/FileLinkModal */ "./client/src/components/LinkModal/FileLinkModal.js"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const registerComponents = () => {
  _Injector.default.component.registerMany({
    LinkPicker: _LinkPicker.default,
    LinkField: _LinkField.default,
    MultiLinkPicker: _MultiLinkPicker.default,
    MultiLinkField: _MultiLinkField.default,
    'LinkModal.FormBuilderModal': _LinkModal.default,
    'LinkModal.InsertMediaModal': _FileLinkModal.default
  });
};
var _default = registerComponents;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/boot/registerQueries.js":
/*!********************************************!*\
  !*** ./client/src/boot/registerQueries.js ***!
  \********************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _Injector = _interopRequireDefault(__webpack_require__(/*! lib/Injector */ "lib/Injector"));
var _readLinkTypes = _interopRequireDefault(__webpack_require__(/*! state/linkTypes/readLinkTypes */ "./client/src/state/linkTypes/readLinkTypes.js"));
var _readLinkDescription = _interopRequireDefault(__webpack_require__(/*! state/linkDescription/readLinkDescription */ "./client/src/state/linkDescription/readLinkDescription.js"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const registerQueries = () => {
  _Injector.default.query.register('readLinkTypes', _readLinkTypes.default);
  _Injector.default.query.register('readLinkDescription', _readLinkDescription.default);
};
var _default = registerQueries;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/boot/registerReducers.js":
/*!*********************************************!*\
  !*** ./client/src/boot/registerReducers.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, exports) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
const registerReducers = () => {};
var _default = registerReducers;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/AbstractLinkField/AbstractLinkField.js":
/*!**********************************************************************!*\
  !*** ./client/src/components/AbstractLinkField/AbstractLinkField.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports.linkFieldPropTypes = exports["default"] = void 0;
var _react = _interopRequireWildcard(__webpack_require__(/*! react */ "react"));
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _LinkType = _interopRequireDefault(__webpack_require__(/*! ../../types/LinkType */ "./client/src/types/LinkType.js"));
var _LinkSummary = _interopRequireDefault(__webpack_require__(/*! ../../types/LinkSummary */ "./client/src/types/LinkSummary.js"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function (nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }
function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || typeof obj !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }
const AbstractLinkField = _ref => {
  let {
    id,
    loading,
    Loading,
    Picker,
    onChange,
    types,
    clearLinkData,
    buildLinkProps,
    updateLinkData,
    selectLinkData
  } = _ref;
  if (loading) {
    return _react.default.createElement(Loading, null);
  }
  const [editingId, setEditingId] = (0, _react.useState)(false);
  const [newTypeKey, setNewTypeKey] = (0, _react.useState)('');
  const selectedLinkData = selectLinkData(editingId);
  const modalType = types[selectedLinkData && selectedLinkData.typeKey || newTypeKey];
  const onClear = (event, linkId) => {
    if (typeof onChange === 'function') {
      onChange(event, {
        id,
        value: clearLinkData(linkId)
      });
    }
  };
  const linkProps = {
    ...buildLinkProps(),
    onEdit: linkId => {
      setEditingId(linkId);
    },
    onClear,
    onSelect: key => {
      setNewTypeKey(key);
      setEditingId(true);
    },
    types: Object.values(types)
  };
  const onModalSubmit = submittedData => {
    const {
      SecurityID,
      action_insert,
      ...newLinkData
    } = submittedData;
    if (typeof onChange === 'function') {
      onChange(undefined, {
        id,
        value: updateLinkData(newLinkData)
      });
    }
    setEditingId(false);
    setNewTypeKey('');
    return Promise.resolve();
  };
  const modalProps = {
    type: modalType,
    editing: editingId !== false,
    onSubmit: onModalSubmit,
    onClosed: () => {
      setEditingId(false);
      return Promise.resolve();
    },
    data: selectedLinkData
  };
  const handlerName = modalType ? modalType.handlerName : 'FormBuilderModal';
  const LinkModal = (0, _Injector.loadComponent)(`LinkModal.${handlerName}`);
  return _react.default.createElement(_react.Fragment, null, _react.default.createElement(Picker, linkProps), _react.default.createElement(LinkModal, modalProps));
};
const linkFieldPropTypes = {
  id: _propTypes.default.string.isRequired,
  loading: _propTypes.default.bool,
  Loading: _propTypes.default.elementType,
  data: _propTypes.default.any,
  Picker: _propTypes.default.elementType,
  onChange: _propTypes.default.func,
  types: _propTypes.default.objectOf(_LinkType.default),
  linkDescriptions: _propTypes.default.arrayOf(_LinkSummary.default)
};
exports.linkFieldPropTypes = linkFieldPropTypes;
AbstractLinkField.propTypes = {
  ...linkFieldPropTypes,
  clearLinkData: _propTypes.default.func.isRequired,
  buildLinkProps: _propTypes.default.func.isRequired,
  updateLinkData: _propTypes.default.func.isRequired,
  selectLinkData: _propTypes.default.func.isRequired
};
var _default = AbstractLinkField;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/AbstractLinkField/linkFieldHOC.js":
/*!*****************************************************************!*\
  !*** ./client/src/components/AbstractLinkField/linkFieldHOC.js ***!
  \*****************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _redux = __webpack_require__(/*! redux */ "redux");
var _hoc = __webpack_require__(/*! @apollo/client/react/hoc */ "@apollo/client/react/hoc");
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
var _FieldHolder = _interopRequireDefault(__webpack_require__(/*! components/FieldHolder/FieldHolder */ "components/FieldHolder/FieldHolder"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
const stringifyData = Component => _ref => {
  let {
    data,
    value,
    ...props
  } = _ref;
  let dataValue = value || data;
  console.dir(dataValue);
  if (typeof dataValue === 'string') {
    dataValue = JSON.parse(dataValue);
  }
  return _react.default.createElement(Component, _extends({
    dataStr: JSON.stringify(dataValue)
  }, props, {
    data: dataValue
  }));
};
const linkFieldHOC = (0, _redux.compose)(stringifyData, (0, _Injector.injectGraphql)('readLinkTypes'), (0, _Injector.injectGraphql)('readLinkDescription'), _hoc.withApollo, _FieldHolder.default);
var _default = linkFieldHOC;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkBox/LinkBox.js":
/*!**************************************************!*\
  !*** ./client/src/components/LinkBox/LinkBox.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _classnames = _interopRequireDefault(__webpack_require__(/*! classnames */ "classnames"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const LinkBox = _ref => {
  let {
    className,
    children
  } = _ref;
  return _react.default.createElement("div", {
    className: (0, _classnames.default)('link-box', 'form-control', className)
  }, children);
};
LinkBox.propTypes = {
  className: _propTypes.default.string
};
var _default = LinkBox;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkField/LinkField.js":
/*!******************************************************!*\
  !*** ./client/src/components/LinkField/LinkField.js ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = exports.Component = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _redux = __webpack_require__(/*! redux */ "redux");
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _LinkData = _interopRequireDefault(__webpack_require__(/*! ../../types/LinkData */ "./client/src/types/LinkData.js"));
var _AbstractLinkField = _interopRequireWildcard(__webpack_require__(/*! ../AbstractLinkField/AbstractLinkField */ "./client/src/components/AbstractLinkField/AbstractLinkField.js"));
var _linkFieldHOC = _interopRequireDefault(__webpack_require__(/*! ../AbstractLinkField/linkFieldHOC */ "./client/src/components/AbstractLinkField/linkFieldHOC.js"));
function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function (nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }
function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || typeof obj !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
const LinkField = props => {
  const staticProps = {
    buildLinkProps: () => {
      const {
        data,
        linkDescriptions,
        types
      } = props;
      const {
        typeKey
      } = data;
      const type = types[typeKey];
      const linkDescription = linkDescriptions.length > 0 ? linkDescriptions[0] : {};
      const {
        title,
        description
      } = linkDescription;
      return {
        title,
        description,
        type: type || undefined
      };
    },
    clearLinkData: () => ({}),
    updateLinkData: newLinkData => newLinkData,
    selectLinkData: () => props.data
  };
  return _react.default.createElement(_AbstractLinkField.default, _extends({}, props, staticProps));
};
exports.Component = LinkField;
LinkField.propTypes = {
  ..._AbstractLinkField.linkFieldPropTypes,
  data: _LinkData.default
};
var _default = (0, _redux.compose)((0, _Injector.inject)(['LinkPicker', 'Loading'], (LinkPicker, Loading) => ({
  Picker: LinkPicker,
  Loading
})), _linkFieldHOC.default)(LinkField);
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkModal/FileLinkModal.js":
/*!**********************************************************!*\
  !*** ./client/src/components/LinkModal/FileLinkModal.js ***!
  \**********************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _react = _interopRequireWildcard(__webpack_require__(/*! react */ "react"));
var _InsertMediaModal = _interopRequireDefault(__webpack_require__(/*! containers/InsertMediaModal/InsertMediaModal */ "containers/InsertMediaModal/InsertMediaModal"));
var _reactRedux = __webpack_require__(/*! react-redux */ "react-redux");
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function (nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }
function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || typeof obj !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
const FileLinkModal = _ref => {
  let {
    type,
    editing,
    data,
    actions,
    onSubmit,
    ...props
  } = _ref;
  if (!type) {
    return false;
  }
  (0, _react.useEffect)(() => {
    if (editing) {
      actions.initModal();
    } else {
      actions.reset();
    }
  }, [editing]);
  const attrs = data ? {
    ID: data.FileID,
    Description: data.Title,
    TargetBlank: !!data.OpenInNew
  } : {};
  const onInsert = _ref2 => {
    let {
      ID,
      Description,
      TargetBlank
    } = _ref2;
    return onSubmit({
      FileID: ID,
      ID: data ? data.ID : undefined,
      Title: Description,
      OpenInNew: TargetBlank,
      typeKey: type.key
    }, '', () => {});
  };
  return _react.default.createElement(_InsertMediaModal.default, _extends({
    isOpen: editing,
    type: "insert-link",
    title: false,
    bodyClassName: "modal__dialog",
    className: "insert-link__dialog-wrapper--internal",
    fileAttributes: attrs,
    onInsert: onInsert
  }, props));
};
function mapStateToProps() {
  return {};
}
function mapDispatchToProps(dispatch) {
  return {
    actions: {
      initModal: () => dispatch({
        type: 'INIT_FORM_SCHEMA_STACK',
        payload: {
          formSchema: {
            type: 'insert-link',
            nextType: 'admin'
          }
        }
      }),
      reset: () => dispatch({
        type: 'RESET'
      })
    }
  };
}
var _default = (0, _reactRedux.connect)(mapStateToProps, mapDispatchToProps)(FileLinkModal);
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkModal/LinkModal.js":
/*!******************************************************!*\
  !*** ./client/src/components/LinkModal/LinkModal.js ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _FormBuilderModal = _interopRequireDefault(__webpack_require__(/*! components/FormBuilderModal/FormBuilderModal */ "components/FormBuilderModal/FormBuilderModal"));
var _url = _interopRequireDefault(__webpack_require__(/*! url */ "url"));
var _qs = _interopRequireDefault(__webpack_require__(/*! qs */ "qs"));
var _Config = _interopRequireDefault(__webpack_require__(/*! lib/Config */ "lib/Config"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
const leftAndMain = 'SilverStripe\\Admin\\LeftAndMain';
const buildSchemaUrl = (key, data) => {
  const {
    schemaUrl
  } = _Config.default.getSection(leftAndMain).form.DynamicLink;
  const parsedURL = _url.default.parse(schemaUrl);
  const parsedQs = _qs.default.parse(parsedURL.query);
  parsedQs.key = key;
  if (data) {
    parsedQs.data = JSON.stringify(data);
  }
  return _url.default.format({
    ...parsedURL,
    search: _qs.default.stringify(parsedQs)
  });
};
const LinkModal = _ref => {
  let {
    type,
    editing,
    data,
    ...props
  } = _ref;
  if (!type) {
    return false;
  }
  return _react.default.createElement(_FormBuilderModal.default, _extends({
    title: type.title,
    isOpen: editing,
    schemaUrl: buildSchemaUrl(type.key, data),
    identifier: "Link.EditingLinkInfo"
  }, props));
};
var _default = LinkModal;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkPicker/LinkPicker.js":
/*!********************************************************!*\
  !*** ./client/src/components/LinkPicker/LinkPicker.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = exports.Component = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _classnames = _interopRequireDefault(__webpack_require__(/*! classnames */ "classnames"));
var _LinkPickerMenu = _interopRequireDefault(__webpack_require__(/*! ./LinkPickerMenu */ "./client/src/components/LinkPicker/LinkPickerMenu.js"));
var _LinkPickerTitle = _interopRequireDefault(__webpack_require__(/*! ./LinkPickerTitle */ "./client/src/components/LinkPicker/LinkPickerTitle.js"));
var _LinkBox = _interopRequireDefault(__webpack_require__(/*! ../LinkBox/LinkBox */ "./client/src/components/LinkBox/LinkBox.js"));
var _LinkType = _interopRequireDefault(__webpack_require__(/*! ../../types/LinkType */ "./client/src/types/LinkType.js"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const LinkPicker = _ref => {
  let {
    types,
    onSelect,
    title,
    description,
    type,
    onEdit,
    onClear
  } = _ref;
  return _react.default.createElement(_LinkBox.default, {
    className: (0, _classnames.default)('link-picker', {
      'link-picker--selected': type
    })
  }, type ? _react.default.createElement(_LinkPickerTitle.default, {
    description: description,
    title: title,
    type: type,
    onClear: onClear,
    onClick: () => onEdit && onEdit()
  }) : _react.default.createElement(_LinkPickerMenu.default, {
    types: types,
    onSelect: onSelect
  }));
};
exports.Component = LinkPicker;
LinkPicker.propTypes = {
  ..._LinkPickerMenu.default.propTypes,
  onEdit: _propTypes.default.func,
  onClear: _propTypes.default.func,
  title: _propTypes.default.string,
  description: _propTypes.default.string,
  type: _LinkType.default
};
var _default = LinkPicker;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkPicker/LinkPickerMenu.js":
/*!************************************************************!*\
  !*** ./client/src/components/LinkPicker/LinkPickerMenu.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _i18n = _interopRequireDefault(__webpack_require__(/*! i18n */ "i18n"));
var _react = _interopRequireWildcard(__webpack_require__(/*! react */ "react"));
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _reactstrap = __webpack_require__(/*! reactstrap */ "reactstrap");
var _LinkType = _interopRequireDefault(__webpack_require__(/*! types/LinkType */ "./client/src/types/LinkType.js"));
function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function (nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }
function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || typeof obj !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const LinkPickerMenu = _ref => {
  let {
    types,
    onSelect
  } = _ref;
  const [isOpen, setIsOpen] = (0, _react.useState)(false);
  const toggle = () => setIsOpen(prevState => !prevState);
  return _react.default.createElement(_reactstrap.Dropdown, {
    isOpen: isOpen,
    toggle: toggle,
    className: "link-menu"
  }, _react.default.createElement(_reactstrap.DropdownToggle, {
    className: "link-menu__toggle font-icon-link",
    caret: true
  }, _i18n.default._t('Link.ADD_LINK', 'Add Link')), _react.default.createElement(_reactstrap.DropdownMenu, null, types.map(_ref2 => {
    let {
      key,
      title,
      icon
    } = _ref2;
    return _react.default.createElement(_reactstrap.DropdownItem, {
      className: `font-icon-${icon || 'link'}`,
      key: key,
      onClick: () => onSelect(key)
    }, title);
  })));
};
LinkPickerMenu.propTypes = {
  types: _propTypes.default.arrayOf(_LinkType.default).isRequired,
  onSelect: _propTypes.default.func.isRequired
};
var _default = LinkPickerMenu;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/LinkPicker/LinkPickerTitle.js":
/*!*************************************************************!*\
  !*** ./client/src/components/LinkPicker/LinkPickerTitle.js ***!
  \*************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _i18n = _interopRequireDefault(__webpack_require__(/*! i18n */ "i18n"));
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _LinkType = _interopRequireDefault(__webpack_require__(/*! types/LinkType */ "./client/src/types/LinkType.js"));
var _reactstrap = __webpack_require__(/*! reactstrap */ "reactstrap");
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const stopPropagation = fn => e => {
  e.nativeEvent.stopImmediatePropagation();
  e.preventDefault();
  e.nativeEvent.preventDefault();
  e.stopPropagation();
  if (fn) {
    fn();
  }
};
const LinkPickerTitle = _ref => {
  let {
    title,
    type,
    description,
    onClear,
    onClick,
    className
  } = _ref;
  return _react.default.createElement(_reactstrap.Button, {
    className: classnames('link-title', `font-icon-${type.icon || 'link'}`, className),
    color: "secondary",
    onClick: stopPropagation(onClick)
  }, _react.default.createElement("div", {
    className: "link-title__detail"
  }, _react.default.createElement("div", {
    className: "link-title__title"
  }, title), _react.default.createElement("small", {
    className: "link-title__type"
  }, type.title, ":\xA0", _react.default.createElement("span", {
    className: "link-title__url"
  }, description))), _react.default.createElement(_reactstrap.Button, {
    tag: "a",
    className: "link-title__clear",
    color: "link",
    onClick: stopPropagation(onClear)
  }, _i18n.default._t('Link.CLEAR', 'Clear')));
};
LinkPickerTitle.propTypes = {
  title: _propTypes.default.string.isRequired,
  type: _LinkType.default,
  description: _propTypes.default.string,
  onClear: _propTypes.default.func,
  onClick: _propTypes.default.func
};
LinkPickerTitle.defaultProps = {
  type: {}
};
var _default = LinkPickerTitle;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/MultiLinkField/MultiLinkField.js":
/*!****************************************************************!*\
  !*** ./client/src/components/MultiLinkField/MultiLinkField.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = exports.Component = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _redux = __webpack_require__(/*! redux */ "redux");
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _uuid = __webpack_require__(/*! uuid */ "./node_modules/uuid/dist/esm-browser/index.js");
var _LinkData = _interopRequireDefault(__webpack_require__(/*! ../../types/LinkData */ "./client/src/types/LinkData.js"));
var _AbstractLinkField = _interopRequireWildcard(__webpack_require__(/*! ../AbstractLinkField/AbstractLinkField */ "./client/src/components/AbstractLinkField/AbstractLinkField.js"));
var _linkFieldHOC = _interopRequireDefault(__webpack_require__(/*! ../AbstractLinkField/linkFieldHOC */ "./client/src/components/AbstractLinkField/linkFieldHOC.js"));
function _getRequireWildcardCache(nodeInterop) { if (typeof WeakMap !== "function") return null; var cacheBabelInterop = new WeakMap(); var cacheNodeInterop = new WeakMap(); return (_getRequireWildcardCache = function (nodeInterop) { return nodeInterop ? cacheNodeInterop : cacheBabelInterop; })(nodeInterop); }
function _interopRequireWildcard(obj, nodeInterop) { if (!nodeInterop && obj && obj.__esModule) { return obj; } if (obj === null || typeof obj !== "object" && typeof obj !== "function") { return { default: obj }; } var cache = _getRequireWildcardCache(nodeInterop); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (key !== "default" && Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj.default = obj; if (cache) { cache.set(obj, newObj); } return newObj; }
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
function mergeLinkDataWithDescription(links, descriptions) {
  return links.map(link => {
    const description = descriptions.find(_ref => {
      let {
        id
      } = _ref;
      return id.toString() === link.ID.toString();
    });
    return {
      ...link,
      ...description
    };
  });
}
const MultiLinkField = props => {
  const staticProps = {
    buildLinkProps: () => ({
      links: mergeLinkDataWithDescription(props.data, props.linkDescriptions)
    }),
    clearLinkData: linkId => props.data.filter(_ref2 => {
      let {
        ID
      } = _ref2;
      return ID !== linkId;
    }),
    updateLinkData: newLinkData => {
      const {
        data
      } = props;
      return newLinkData.ID ? data.map(oldLink => oldLink.ID === newLinkData.ID ? newLinkData : oldLink) : [...data, {
        ...newLinkData,
        ID: (0, _uuid.v4)(),
        isNew: true
      }];
    },
    selectLinkData: editingId => props.data.find(_ref3 => {
      let {
        ID
      } = _ref3;
      return ID === editingId;
    }) || undefined
  };
  return _react.default.createElement(_AbstractLinkField.default, _extends({}, props, staticProps));
};
exports.Component = MultiLinkField;
MultiLinkField.propTypes = {
  ..._AbstractLinkField.linkFieldPropTypes,
  data: _propTypes.default.arrayOf(_LinkData.default)
};
var _default = (0, _redux.compose)((0, _Injector.inject)(['MultiLinkPicker', 'Loading'], (MultiLinkPicker, Loading) => ({
  Picker: MultiLinkPicker,
  Loading
})), _linkFieldHOC.default)(MultiLinkField);
exports["default"] = _default;

/***/ }),

/***/ "./client/src/components/MultiLinkPicker/MultiLinkPicker.js":
/*!******************************************************************!*\
  !*** ./client/src/components/MultiLinkPicker/MultiLinkPicker.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = exports.Component = void 0;
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
var _LinkPickerMenu = _interopRequireDefault(__webpack_require__(/*! ../LinkPicker/LinkPickerMenu */ "./client/src/components/LinkPicker/LinkPickerMenu.js"));
var _LinkPickerTitle = _interopRequireDefault(__webpack_require__(/*! ../LinkPicker/LinkPickerTitle */ "./client/src/components/LinkPicker/LinkPickerTitle.js"));
var _LinkBox = _interopRequireDefault(__webpack_require__(/*! ../LinkBox/LinkBox */ "./client/src/components/LinkBox/LinkBox.js"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
const LinkPicker = _ref => {
  let {
    types,
    onSelect,
    links,
    onEdit,
    onClear
  } = _ref;
  return _react.default.createElement("div", {
    className: "multi-link-picker"
  }, _react.default.createElement(_LinkBox.default, {
    className: "multi-link-picker__picker"
  }, _react.default.createElement(_LinkPickerMenu.default, {
    types: types,
    onSelect: onSelect
  })), links.length > 0 && _react.default.createElement(_LinkBox.default, {
    className: "multi-link-picker__list"
  }, links.map(_ref2 => {
    let {
      ID,
      ...link
    } = _ref2;
    return _react.default.createElement(_LinkPickerTitle.default, _extends({}, link, {
      className: "multi-link-picker__link",
      type: types.find(type => type.key === link.typeKey),
      key: `${ID} ${link.description}`,
      onClear: event => onClear(event, ID),
      onClick: () => onEdit(ID)
    }));
  })));
};
exports.Component = LinkPicker;
LinkPicker.propTypes = {
  ..._LinkPickerMenu.default.propTypes,
  links: _propTypes.default.arrayOf(_propTypes.default.shape(_LinkPickerTitle.default.propTypes)),
  onEdit: _propTypes.default.func,
  onClear: _propTypes.default.func
};
var _default = LinkPicker;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/entwine/JsonField.js":
/*!*****************************************!*\
  !*** ./client/src/entwine/JsonField.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __unused_webpack_exports, __webpack_require__) {



var _jquery = _interopRequireDefault(__webpack_require__(/*! jquery */ "jquery"));
var _react = _interopRequireDefault(__webpack_require__(/*! react */ "react"));
var _client = _interopRequireDefault(__webpack_require__(/*! react-dom/client */ "react-dom/client"));
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
function _extends() { _extends = Object.assign ? Object.assign.bind() : function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }
_jquery.default.entwine('ss', $ => {
  $('.js-injector-boot .entwine-jsonfield').entwine({
    Component: null,
    Root: null,
    onmatch() {
      const cmsContent = this.closest('.cms-content').attr('id');
      const context = cmsContent ? {
        context: cmsContent
      } : {};
      const schemaComponent = this.data('schema-component');
      const ReactField = (0, _Injector.loadComponent)(schemaComponent, context);
      this.setComponent(ReactField);
      this.setRoot(_client.default.createRoot(this[0]));
      this._super();
      this.refresh();
    },
    refresh() {
      const props = this.getProps();
      const ReactField = this.getComponent();
      const Root = this.getRoot();
      Root.render(_react.default.createElement(ReactField, _extends({}, props, {
        noHolder: true
      })));
    },
    handleChange(event, _ref) {
      let {
        value
      } = _ref;
      const fieldID = $(this).data('field-id');
      $(`#${fieldID}`).val(JSON.stringify(value)).trigger('change');
      this.refresh();
    },
    getProps() {
      const fieldID = $(this).data('field-id');
      const dataStr = $(`#${fieldID}`).val();
      const value = dataStr ? JSON.parse(dataStr) : undefined;
      return {
        id: fieldID,
        value,
        onChange: this.handleChange.bind(this)
      };
    },
    onunmatch() {
      const Root = this.getRoot();
      Root.unmount();
    }
  });
});

/***/ }),

/***/ "./client/src/state/linkDescription/readLinkDescription.js":
/*!*****************************************************************!*\
  !*** ./client/src/state/linkDescription/readLinkDescription.js ***!
  \*****************************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
const apolloConfig = {
  props(props) {
    const {
      data: {
        error,
        readLinkDescription,
        loading: networkLoading
      }
    } = props;
    const errors = error && error.graphQLErrors && error.graphQLErrors.map(graphQLError => graphQLError.message);
    return {
      loading: networkLoading,
      linkDescriptions: readLinkDescription || [],
      graphQLErrors: errors
    };
  }
};
const {
  READ
} = _Injector.graphqlTemplates;
const query = {
  apolloConfig,
  templateName: READ,
  pluralName: 'LinkDescription',
  pagination: false,
  params: {
    dataStr: 'String!'
  },
  args: {
    root: {
      dataStr: 'dataStr'
    }
  },
  fields: ['id', 'description', 'title']
};
var _default = query;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/state/linkTypes/readLinkTypes.js":
/*!*****************************************************!*\
  !*** ./client/src/state/linkTypes/readLinkTypes.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _Injector = __webpack_require__(/*! lib/Injector */ "lib/Injector");
const apolloConfig = {
  props(props) {
    const {
      data: {
        error,
        readLinkTypes,
        loading: networkLoading
      }
    } = props;
    const errors = error && error.graphQLErrors && error.graphQLErrors.map(graphQLError => graphQLError.message);
    const types = readLinkTypes ? readLinkTypes.reduce((accumulator, type) => ({
      ...accumulator,
      [type.key]: type
    }), {}) : {};
    return {
      loading: networkLoading,
      types,
      graphQLErrors: errors
    };
  }
};
const {
  READ
} = _Injector.graphqlTemplates;
const query = {
  apolloConfig,
  templateName: READ,
  pluralName: 'LinkTypes',
  pagination: false,
  params: {
    keys: '[ID]'
  },
  args: {
    root: {
      keys: 'keys'
    }
  },
  fields: ['key', 'title', 'handlerName', 'icon']
};
var _default = query;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/types/LinkData.js":
/*!**************************************!*\
  !*** ./client/src/types/LinkData.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const LinkData = _propTypes.default.shape({
  typeKey: _propTypes.default.string,
  Title: _propTypes.default.string,
  OpenInNew: _propTypes.default.bool
});
var _default = LinkData;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/types/LinkSummary.js":
/*!*****************************************!*\
  !*** ./client/src/types/LinkSummary.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const LinkSummary = _propTypes.default.shape({
  title: _propTypes.default.string,
  description: _propTypes.default.string
});
var _default = LinkSummary;
exports["default"] = _default;

/***/ }),

/***/ "./client/src/types/LinkType.js":
/*!**************************************!*\
  !*** ./client/src/types/LinkType.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, exports, __webpack_require__) {



Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _propTypes = _interopRequireDefault(__webpack_require__(/*! prop-types */ "prop-types"));
function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }
const LinkType = _propTypes.default.shape({
  key: _propTypes.default.string.isRequired,
  icon: _propTypes.default.string,
  title: _propTypes.default.string.isRequired
});
var _default = LinkType;
exports["default"] = _default;

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/index.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/index.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "NIL": function() { return /* reexport safe */ _nil_js__WEBPACK_IMPORTED_MODULE_4__["default"]; },
/* harmony export */   "parse": function() { return /* reexport safe */ _parse_js__WEBPACK_IMPORTED_MODULE_8__["default"]; },
/* harmony export */   "stringify": function() { return /* reexport safe */ _stringify_js__WEBPACK_IMPORTED_MODULE_7__["default"]; },
/* harmony export */   "v1": function() { return /* reexport safe */ _v1_js__WEBPACK_IMPORTED_MODULE_0__["default"]; },
/* harmony export */   "v3": function() { return /* reexport safe */ _v3_js__WEBPACK_IMPORTED_MODULE_1__["default"]; },
/* harmony export */   "v4": function() { return /* reexport safe */ _v4_js__WEBPACK_IMPORTED_MODULE_2__["default"]; },
/* harmony export */   "v5": function() { return /* reexport safe */ _v5_js__WEBPACK_IMPORTED_MODULE_3__["default"]; },
/* harmony export */   "validate": function() { return /* reexport safe */ _validate_js__WEBPACK_IMPORTED_MODULE_6__["default"]; },
/* harmony export */   "version": function() { return /* reexport safe */ _version_js__WEBPACK_IMPORTED_MODULE_5__["default"]; }
/* harmony export */ });
/* harmony import */ var _v1_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./v1.js */ "./node_modules/uuid/dist/esm-browser/v1.js");
/* harmony import */ var _v3_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./v3.js */ "./node_modules/uuid/dist/esm-browser/v3.js");
/* harmony import */ var _v4_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./v4.js */ "./node_modules/uuid/dist/esm-browser/v4.js");
/* harmony import */ var _v5_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./v5.js */ "./node_modules/uuid/dist/esm-browser/v5.js");
/* harmony import */ var _nil_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./nil.js */ "./node_modules/uuid/dist/esm-browser/nil.js");
/* harmony import */ var _version_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./version.js */ "./node_modules/uuid/dist/esm-browser/version.js");
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");
/* harmony import */ var _parse_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./parse.js */ "./node_modules/uuid/dist/esm-browser/parse.js");










/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/md5.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/md5.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/*
 * Browser-compatible JavaScript MD5
 *
 * Modification of JavaScript MD5
 * https://github.com/blueimp/JavaScript-MD5
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 *
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */
function md5(bytes) {
  if (typeof bytes === 'string') {
    var msg = unescape(encodeURIComponent(bytes)); // UTF8 escape

    bytes = new Uint8Array(msg.length);

    for (var i = 0; i < msg.length; ++i) {
      bytes[i] = msg.charCodeAt(i);
    }
  }

  return md5ToHexEncodedArray(wordsToMd5(bytesToWords(bytes), bytes.length * 8));
}
/*
 * Convert an array of little-endian words to an array of bytes
 */


function md5ToHexEncodedArray(input) {
  var output = [];
  var length32 = input.length * 32;
  var hexTab = '0123456789abcdef';

  for (var i = 0; i < length32; i += 8) {
    var x = input[i >> 5] >>> i % 32 & 0xff;
    var hex = parseInt(hexTab.charAt(x >>> 4 & 0x0f) + hexTab.charAt(x & 0x0f), 16);
    output.push(hex);
  }

  return output;
}
/**
 * Calculate output length with padding and bit length
 */


function getOutputLength(inputLength8) {
  return (inputLength8 + 64 >>> 9 << 4) + 14 + 1;
}
/*
 * Calculate the MD5 of an array of little-endian words, and a bit length.
 */


function wordsToMd5(x, len) {
  /* append padding */
  x[len >> 5] |= 0x80 << len % 32;
  x[getOutputLength(len) - 1] = len;
  var a = 1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d = 271733878;

  for (var i = 0; i < x.length; i += 16) {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;
    a = md5ff(a, b, c, d, x[i], 7, -680876936);
    d = md5ff(d, a, b, c, x[i + 1], 12, -389564586);
    c = md5ff(c, d, a, b, x[i + 2], 17, 606105819);
    b = md5ff(b, c, d, a, x[i + 3], 22, -1044525330);
    a = md5ff(a, b, c, d, x[i + 4], 7, -176418897);
    d = md5ff(d, a, b, c, x[i + 5], 12, 1200080426);
    c = md5ff(c, d, a, b, x[i + 6], 17, -1473231341);
    b = md5ff(b, c, d, a, x[i + 7], 22, -45705983);
    a = md5ff(a, b, c, d, x[i + 8], 7, 1770035416);
    d = md5ff(d, a, b, c, x[i + 9], 12, -1958414417);
    c = md5ff(c, d, a, b, x[i + 10], 17, -42063);
    b = md5ff(b, c, d, a, x[i + 11], 22, -1990404162);
    a = md5ff(a, b, c, d, x[i + 12], 7, 1804603682);
    d = md5ff(d, a, b, c, x[i + 13], 12, -40341101);
    c = md5ff(c, d, a, b, x[i + 14], 17, -1502002290);
    b = md5ff(b, c, d, a, x[i + 15], 22, 1236535329);
    a = md5gg(a, b, c, d, x[i + 1], 5, -165796510);
    d = md5gg(d, a, b, c, x[i + 6], 9, -1069501632);
    c = md5gg(c, d, a, b, x[i + 11], 14, 643717713);
    b = md5gg(b, c, d, a, x[i], 20, -373897302);
    a = md5gg(a, b, c, d, x[i + 5], 5, -701558691);
    d = md5gg(d, a, b, c, x[i + 10], 9, 38016083);
    c = md5gg(c, d, a, b, x[i + 15], 14, -660478335);
    b = md5gg(b, c, d, a, x[i + 4], 20, -405537848);
    a = md5gg(a, b, c, d, x[i + 9], 5, 568446438);
    d = md5gg(d, a, b, c, x[i + 14], 9, -1019803690);
    c = md5gg(c, d, a, b, x[i + 3], 14, -187363961);
    b = md5gg(b, c, d, a, x[i + 8], 20, 1163531501);
    a = md5gg(a, b, c, d, x[i + 13], 5, -1444681467);
    d = md5gg(d, a, b, c, x[i + 2], 9, -51403784);
    c = md5gg(c, d, a, b, x[i + 7], 14, 1735328473);
    b = md5gg(b, c, d, a, x[i + 12], 20, -1926607734);
    a = md5hh(a, b, c, d, x[i + 5], 4, -378558);
    d = md5hh(d, a, b, c, x[i + 8], 11, -2022574463);
    c = md5hh(c, d, a, b, x[i + 11], 16, 1839030562);
    b = md5hh(b, c, d, a, x[i + 14], 23, -35309556);
    a = md5hh(a, b, c, d, x[i + 1], 4, -1530992060);
    d = md5hh(d, a, b, c, x[i + 4], 11, 1272893353);
    c = md5hh(c, d, a, b, x[i + 7], 16, -155497632);
    b = md5hh(b, c, d, a, x[i + 10], 23, -1094730640);
    a = md5hh(a, b, c, d, x[i + 13], 4, 681279174);
    d = md5hh(d, a, b, c, x[i], 11, -358537222);
    c = md5hh(c, d, a, b, x[i + 3], 16, -722521979);
    b = md5hh(b, c, d, a, x[i + 6], 23, 76029189);
    a = md5hh(a, b, c, d, x[i + 9], 4, -640364487);
    d = md5hh(d, a, b, c, x[i + 12], 11, -421815835);
    c = md5hh(c, d, a, b, x[i + 15], 16, 530742520);
    b = md5hh(b, c, d, a, x[i + 2], 23, -995338651);
    a = md5ii(a, b, c, d, x[i], 6, -198630844);
    d = md5ii(d, a, b, c, x[i + 7], 10, 1126891415);
    c = md5ii(c, d, a, b, x[i + 14], 15, -1416354905);
    b = md5ii(b, c, d, a, x[i + 5], 21, -57434055);
    a = md5ii(a, b, c, d, x[i + 12], 6, 1700485571);
    d = md5ii(d, a, b, c, x[i + 3], 10, -1894986606);
    c = md5ii(c, d, a, b, x[i + 10], 15, -1051523);
    b = md5ii(b, c, d, a, x[i + 1], 21, -2054922799);
    a = md5ii(a, b, c, d, x[i + 8], 6, 1873313359);
    d = md5ii(d, a, b, c, x[i + 15], 10, -30611744);
    c = md5ii(c, d, a, b, x[i + 6], 15, -1560198380);
    b = md5ii(b, c, d, a, x[i + 13], 21, 1309151649);
    a = md5ii(a, b, c, d, x[i + 4], 6, -145523070);
    d = md5ii(d, a, b, c, x[i + 11], 10, -1120210379);
    c = md5ii(c, d, a, b, x[i + 2], 15, 718787259);
    b = md5ii(b, c, d, a, x[i + 9], 21, -343485551);
    a = safeAdd(a, olda);
    b = safeAdd(b, oldb);
    c = safeAdd(c, oldc);
    d = safeAdd(d, oldd);
  }

  return [a, b, c, d];
}
/*
 * Convert an array bytes to an array of little-endian words
 * Characters >255 have their high-byte silently ignored.
 */


function bytesToWords(input) {
  if (input.length === 0) {
    return [];
  }

  var length8 = input.length * 8;
  var output = new Uint32Array(getOutputLength(length8));

  for (var i = 0; i < length8; i += 8) {
    output[i >> 5] |= (input[i / 8] & 0xff) << i % 32;
  }

  return output;
}
/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally
 * to work around bugs in some JS interpreters.
 */


function safeAdd(x, y) {
  var lsw = (x & 0xffff) + (y & 0xffff);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return msw << 16 | lsw & 0xffff;
}
/*
 * Bitwise rotate a 32-bit number to the left.
 */


function bitRotateLeft(num, cnt) {
  return num << cnt | num >>> 32 - cnt;
}
/*
 * These functions implement the four basic operations the algorithm uses.
 */


function md5cmn(q, a, b, x, s, t) {
  return safeAdd(bitRotateLeft(safeAdd(safeAdd(a, q), safeAdd(x, t)), s), b);
}

function md5ff(a, b, c, d, x, s, t) {
  return md5cmn(b & c | ~b & d, a, b, x, s, t);
}

function md5gg(a, b, c, d, x, s, t) {
  return md5cmn(b & d | c & ~d, a, b, x, s, t);
}

function md5hh(a, b, c, d, x, s, t) {
  return md5cmn(b ^ c ^ d, a, b, x, s, t);
}

function md5ii(a, b, c, d, x, s, t) {
  return md5cmn(c ^ (b | ~d), a, b, x, s, t);
}

/* harmony default export */ __webpack_exports__["default"] = (md5);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/nil.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/nil.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ('00000000-0000-0000-0000-000000000000');

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/parse.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/parse.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");


function parse(uuid) {
  if (!(0,_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
    throw TypeError('Invalid UUID');
  }

  var v;
  var arr = new Uint8Array(16); // Parse ########-....-....-....-............

  arr[0] = (v = parseInt(uuid.slice(0, 8), 16)) >>> 24;
  arr[1] = v >>> 16 & 0xff;
  arr[2] = v >>> 8 & 0xff;
  arr[3] = v & 0xff; // Parse ........-####-....-....-............

  arr[4] = (v = parseInt(uuid.slice(9, 13), 16)) >>> 8;
  arr[5] = v & 0xff; // Parse ........-....-####-....-............

  arr[6] = (v = parseInt(uuid.slice(14, 18), 16)) >>> 8;
  arr[7] = v & 0xff; // Parse ........-....-....-####-............

  arr[8] = (v = parseInt(uuid.slice(19, 23), 16)) >>> 8;
  arr[9] = v & 0xff; // Parse ........-....-....-....-############
  // (Use "/" to avoid 32-bit truncation when bit-shifting high-order bytes)

  arr[10] = (v = parseInt(uuid.slice(24, 36), 16)) / 0x10000000000 & 0xff;
  arr[11] = v / 0x100000000 & 0xff;
  arr[12] = v >>> 24 & 0xff;
  arr[13] = v >>> 16 & 0xff;
  arr[14] = v >>> 8 & 0xff;
  arr[15] = v & 0xff;
  return arr;
}

/* harmony default export */ __webpack_exports__["default"] = (parse);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/regex.js":
/*!*****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/regex.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/rng.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/rng.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ rng; }
/* harmony export */ });
// Unique ID creation requires a high quality random # generator. In the browser we therefore
// require the crypto API and do not support built-in fallback to lower quality random number
// generators (like Math.random()).
var getRandomValues;
var rnds8 = new Uint8Array(16);
function rng() {
  // lazy load so that environments that need to polyfill have a chance to do so
  if (!getRandomValues) {
    // getRandomValues needs to be invoked in a context where "this" is a Crypto implementation. Also,
    // find the complete implementation of crypto (msCrypto) on IE11.
    getRandomValues = typeof crypto !== 'undefined' && crypto.getRandomValues && crypto.getRandomValues.bind(crypto) || typeof msCrypto !== 'undefined' && typeof msCrypto.getRandomValues === 'function' && msCrypto.getRandomValues.bind(msCrypto);

    if (!getRandomValues) {
      throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');
    }
  }

  return getRandomValues(rnds8);
}

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/sha1.js":
/*!****************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/sha1.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// Adapted from Chris Veness' SHA1 code at
// http://www.movable-type.co.uk/scripts/sha1.html
function f(s, x, y, z) {
  switch (s) {
    case 0:
      return x & y ^ ~x & z;

    case 1:
      return x ^ y ^ z;

    case 2:
      return x & y ^ x & z ^ y & z;

    case 3:
      return x ^ y ^ z;
  }
}

function ROTL(x, n) {
  return x << n | x >>> 32 - n;
}

function sha1(bytes) {
  var K = [0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6];
  var H = [0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476, 0xc3d2e1f0];

  if (typeof bytes === 'string') {
    var msg = unescape(encodeURIComponent(bytes)); // UTF8 escape

    bytes = [];

    for (var i = 0; i < msg.length; ++i) {
      bytes.push(msg.charCodeAt(i));
    }
  } else if (!Array.isArray(bytes)) {
    // Convert Array-like to Array
    bytes = Array.prototype.slice.call(bytes);
  }

  bytes.push(0x80);
  var l = bytes.length / 4 + 2;
  var N = Math.ceil(l / 16);
  var M = new Array(N);

  for (var _i = 0; _i < N; ++_i) {
    var arr = new Uint32Array(16);

    for (var j = 0; j < 16; ++j) {
      arr[j] = bytes[_i * 64 + j * 4] << 24 | bytes[_i * 64 + j * 4 + 1] << 16 | bytes[_i * 64 + j * 4 + 2] << 8 | bytes[_i * 64 + j * 4 + 3];
    }

    M[_i] = arr;
  }

  M[N - 1][14] = (bytes.length - 1) * 8 / Math.pow(2, 32);
  M[N - 1][14] = Math.floor(M[N - 1][14]);
  M[N - 1][15] = (bytes.length - 1) * 8 & 0xffffffff;

  for (var _i2 = 0; _i2 < N; ++_i2) {
    var W = new Uint32Array(80);

    for (var t = 0; t < 16; ++t) {
      W[t] = M[_i2][t];
    }

    for (var _t = 16; _t < 80; ++_t) {
      W[_t] = ROTL(W[_t - 3] ^ W[_t - 8] ^ W[_t - 14] ^ W[_t - 16], 1);
    }

    var a = H[0];
    var b = H[1];
    var c = H[2];
    var d = H[3];
    var e = H[4];

    for (var _t2 = 0; _t2 < 80; ++_t2) {
      var s = Math.floor(_t2 / 20);
      var T = ROTL(a, 5) + f(s, b, c, d) + e + K[s] + W[_t2] >>> 0;
      e = d;
      d = c;
      c = ROTL(b, 30) >>> 0;
      b = a;
      a = T;
    }

    H[0] = H[0] + a >>> 0;
    H[1] = H[1] + b >>> 0;
    H[2] = H[2] + c >>> 0;
    H[3] = H[3] + d >>> 0;
    H[4] = H[4] + e >>> 0;
  }

  return [H[0] >> 24 & 0xff, H[0] >> 16 & 0xff, H[0] >> 8 & 0xff, H[0] & 0xff, H[1] >> 24 & 0xff, H[1] >> 16 & 0xff, H[1] >> 8 & 0xff, H[1] & 0xff, H[2] >> 24 & 0xff, H[2] >> 16 & 0xff, H[2] >> 8 & 0xff, H[2] & 0xff, H[3] >> 24 & 0xff, H[3] >> 16 & 0xff, H[3] >> 8 & 0xff, H[3] & 0xff, H[4] >> 24 & 0xff, H[4] >> 16 & 0xff, H[4] >> 8 & 0xff, H[4] & 0xff];
}

/* harmony default export */ __webpack_exports__["default"] = (sha1);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/stringify.js":
/*!*********************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/stringify.js ***!
  \*********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");

/**
 * Convert array of 16 byte values to UUID string format of the form:
 * XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
 */

var byteToHex = [];

for (var i = 0; i < 256; ++i) {
  byteToHex.push((i + 0x100).toString(16).substr(1));
}

function stringify(arr) {
  var offset = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
  // Note: Be careful editing this code!  It's been tuned for performance
  // and works in ways you may not expect. See https://github.com/uuidjs/uuid/pull/434
  var uuid = (byteToHex[arr[offset + 0]] + byteToHex[arr[offset + 1]] + byteToHex[arr[offset + 2]] + byteToHex[arr[offset + 3]] + '-' + byteToHex[arr[offset + 4]] + byteToHex[arr[offset + 5]] + '-' + byteToHex[arr[offset + 6]] + byteToHex[arr[offset + 7]] + '-' + byteToHex[arr[offset + 8]] + byteToHex[arr[offset + 9]] + '-' + byteToHex[arr[offset + 10]] + byteToHex[arr[offset + 11]] + byteToHex[arr[offset + 12]] + byteToHex[arr[offset + 13]] + byteToHex[arr[offset + 14]] + byteToHex[arr[offset + 15]]).toLowerCase(); // Consistency check for valid UUID.  If this throws, it's likely due to one
  // of the following:
  // - One or more input array values don't map to a hex octet (leading to
  // "undefined" in the uuid)
  // - Invalid input values for the RFC `version` or `variant` fields

  if (!(0,_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
    throw TypeError('Stringified UUID is invalid');
  }

  return uuid;
}

/* harmony default export */ __webpack_exports__["default"] = (stringify);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v1.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v1.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./rng.js */ "./node_modules/uuid/dist/esm-browser/rng.js");
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");

 // **`v1()` - Generate time-based UUID**
//
// Inspired by https://github.com/LiosK/UUID.js
// and http://docs.python.org/library/uuid.html

var _nodeId;

var _clockseq; // Previous uuid creation time


var _lastMSecs = 0;
var _lastNSecs = 0; // See https://github.com/uuidjs/uuid for API details

function v1(options, buf, offset) {
  var i = buf && offset || 0;
  var b = buf || new Array(16);
  options = options || {};
  var node = options.node || _nodeId;
  var clockseq = options.clockseq !== undefined ? options.clockseq : _clockseq; // node and clockseq need to be initialized to random values if they're not
  // specified.  We do this lazily to minimize issues related to insufficient
  // system entropy.  See #189

  if (node == null || clockseq == null) {
    var seedBytes = options.random || (options.rng || _rng_js__WEBPACK_IMPORTED_MODULE_0__["default"])();

    if (node == null) {
      // Per 4.5, create and 48-bit node id, (47 random bits + multicast bit = 1)
      node = _nodeId = [seedBytes[0] | 0x01, seedBytes[1], seedBytes[2], seedBytes[3], seedBytes[4], seedBytes[5]];
    }

    if (clockseq == null) {
      // Per 4.2.2, randomize (14 bit) clockseq
      clockseq = _clockseq = (seedBytes[6] << 8 | seedBytes[7]) & 0x3fff;
    }
  } // UUID timestamps are 100 nano-second units since the Gregorian epoch,
  // (1582-10-15 00:00).  JSNumbers aren't precise enough for this, so
  // time is handled internally as 'msecs' (integer milliseconds) and 'nsecs'
  // (100-nanoseconds offset from msecs) since unix epoch, 1970-01-01 00:00.


  var msecs = options.msecs !== undefined ? options.msecs : Date.now(); // Per 4.2.1.2, use count of uuid's generated during the current clock
  // cycle to simulate higher resolution clock

  var nsecs = options.nsecs !== undefined ? options.nsecs : _lastNSecs + 1; // Time since last uuid creation (in msecs)

  var dt = msecs - _lastMSecs + (nsecs - _lastNSecs) / 10000; // Per 4.2.1.2, Bump clockseq on clock regression

  if (dt < 0 && options.clockseq === undefined) {
    clockseq = clockseq + 1 & 0x3fff;
  } // Reset nsecs if clock regresses (new clockseq) or we've moved onto a new
  // time interval


  if ((dt < 0 || msecs > _lastMSecs) && options.nsecs === undefined) {
    nsecs = 0;
  } // Per 4.2.1.2 Throw error if too many uuids are requested


  if (nsecs >= 10000) {
    throw new Error("uuid.v1(): Can't create more than 10M uuids/sec");
  }

  _lastMSecs = msecs;
  _lastNSecs = nsecs;
  _clockseq = clockseq; // Per 4.1.4 - Convert from unix epoch to Gregorian epoch

  msecs += 12219292800000; // `time_low`

  var tl = ((msecs & 0xfffffff) * 10000 + nsecs) % 0x100000000;
  b[i++] = tl >>> 24 & 0xff;
  b[i++] = tl >>> 16 & 0xff;
  b[i++] = tl >>> 8 & 0xff;
  b[i++] = tl & 0xff; // `time_mid`

  var tmh = msecs / 0x100000000 * 10000 & 0xfffffff;
  b[i++] = tmh >>> 8 & 0xff;
  b[i++] = tmh & 0xff; // `time_high_and_version`

  b[i++] = tmh >>> 24 & 0xf | 0x10; // include version

  b[i++] = tmh >>> 16 & 0xff; // `clock_seq_hi_and_reserved` (Per 4.2.2 - include variant)

  b[i++] = clockseq >>> 8 | 0x80; // `clock_seq_low`

  b[i++] = clockseq & 0xff; // `node`

  for (var n = 0; n < 6; ++n) {
    b[i + n] = node[n];
  }

  return buf || (0,_stringify_js__WEBPACK_IMPORTED_MODULE_1__["default"])(b);
}

/* harmony default export */ __webpack_exports__["default"] = (v1);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v3.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v3.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _v35_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./v35.js */ "./node_modules/uuid/dist/esm-browser/v35.js");
/* harmony import */ var _md5_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./md5.js */ "./node_modules/uuid/dist/esm-browser/md5.js");


var v3 = (0,_v35_js__WEBPACK_IMPORTED_MODULE_0__["default"])('v3', 0x30, _md5_js__WEBPACK_IMPORTED_MODULE_1__["default"]);
/* harmony default export */ __webpack_exports__["default"] = (v3);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v35.js":
/*!***************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v35.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DNS": function() { return /* binding */ DNS; },
/* harmony export */   "URL": function() { return /* binding */ URL; },
/* harmony export */   "default": function() { return /* export default binding */ __WEBPACK_DEFAULT_EXPORT__; }
/* harmony export */ });
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");
/* harmony import */ var _parse_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./parse.js */ "./node_modules/uuid/dist/esm-browser/parse.js");



function stringToBytes(str) {
  str = unescape(encodeURIComponent(str)); // UTF8 escape

  var bytes = [];

  for (var i = 0; i < str.length; ++i) {
    bytes.push(str.charCodeAt(i));
  }

  return bytes;
}

var DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
var URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
/* harmony default export */ function __WEBPACK_DEFAULT_EXPORT__(name, version, hashfunc) {
  function generateUUID(value, namespace, buf, offset) {
    if (typeof value === 'string') {
      value = stringToBytes(value);
    }

    if (typeof namespace === 'string') {
      namespace = (0,_parse_js__WEBPACK_IMPORTED_MODULE_0__["default"])(namespace);
    }

    if (namespace.length !== 16) {
      throw TypeError('Namespace must be array-like (16 iterable integer values, 0-255)');
    } // Compute hash of namespace and value, Per 4.3
    // Future: Use spread syntax when supported on all platforms, e.g. `bytes =
    // hashfunc([...namespace, ... value])`


    var bytes = new Uint8Array(16 + value.length);
    bytes.set(namespace);
    bytes.set(value, namespace.length);
    bytes = hashfunc(bytes);
    bytes[6] = bytes[6] & 0x0f | version;
    bytes[8] = bytes[8] & 0x3f | 0x80;

    if (buf) {
      offset = offset || 0;

      for (var i = 0; i < 16; ++i) {
        buf[offset + i] = bytes[i];
      }

      return buf;
    }

    return (0,_stringify_js__WEBPACK_IMPORTED_MODULE_1__["default"])(bytes);
  } // Function#name is not settable on some platforms (#270)


  try {
    generateUUID.name = name; // eslint-disable-next-line no-empty
  } catch (err) {} // For CommonJS default export support


  generateUUID.DNS = DNS;
  generateUUID.URL = URL;
  return generateUUID;
}

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v4.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v4.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./rng.js */ "./node_modules/uuid/dist/esm-browser/rng.js");
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/esm-browser/stringify.js");



function v4(options, buf, offset) {
  options = options || {};
  var rnds = options.random || (options.rng || _rng_js__WEBPACK_IMPORTED_MODULE_0__["default"])(); // Per 4.4, set bits for version and `clock_seq_hi_and_reserved`

  rnds[6] = rnds[6] & 0x0f | 0x40;
  rnds[8] = rnds[8] & 0x3f | 0x80; // Copy bytes to buffer, if provided

  if (buf) {
    offset = offset || 0;

    for (var i = 0; i < 16; ++i) {
      buf[offset + i] = rnds[i];
    }

    return buf;
  }

  return (0,_stringify_js__WEBPACK_IMPORTED_MODULE_1__["default"])(rnds);
}

/* harmony default export */ __webpack_exports__["default"] = (v4);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/v5.js":
/*!**************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/v5.js ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _v35_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./v35.js */ "./node_modules/uuid/dist/esm-browser/v35.js");
/* harmony import */ var _sha1_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sha1.js */ "./node_modules/uuid/dist/esm-browser/sha1.js");


var v5 = (0,_v35_js__WEBPACK_IMPORTED_MODULE_0__["default"])('v5', 0x50, _sha1_js__WEBPACK_IMPORTED_MODULE_1__["default"]);
/* harmony default export */ __webpack_exports__["default"] = (v5);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/validate.js":
/*!********************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/validate.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _regex_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./regex.js */ "./node_modules/uuid/dist/esm-browser/regex.js");


function validate(uuid) {
  return typeof uuid === 'string' && _regex_js__WEBPACK_IMPORTED_MODULE_0__["default"].test(uuid);
}

/* harmony default export */ __webpack_exports__["default"] = (validate);

/***/ }),

/***/ "./node_modules/uuid/dist/esm-browser/version.js":
/*!*******************************************************!*\
  !*** ./node_modules/uuid/dist/esm-browser/version.js ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/esm-browser/validate.js");


function version(uuid) {
  if (!(0,_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
    throw TypeError('Invalid UUID');
  }

  return parseInt(uuid.substr(14, 1), 16);
}

/* harmony default export */ __webpack_exports__["default"] = (version);

/***/ }),

/***/ "@apollo/client/react/hoc":
/*!***************************************!*\
  !*** external "ApolloClientReactHoc" ***!
  \***************************************/
/***/ (function(module) {

module.exports = ApolloClientReactHoc;

/***/ }),

/***/ "lib/Config":
/*!*************************!*\
  !*** external "Config" ***!
  \*************************/
/***/ (function(module) {

module.exports = Config;

/***/ }),

/***/ "components/FieldHolder/FieldHolder":
/*!******************************!*\
  !*** external "FieldHolder" ***!
  \******************************/
/***/ (function(module) {

module.exports = FieldHolder;

/***/ }),

/***/ "components/FormBuilderModal/FormBuilderModal":
/*!***********************************!*\
  !*** external "FormBuilderModal" ***!
  \***********************************/
/***/ (function(module) {

module.exports = FormBuilderModal;

/***/ }),

/***/ "lib/Injector":
/*!***************************!*\
  !*** external "Injector" ***!
  \***************************/
/***/ (function(module) {

module.exports = Injector;

/***/ }),

/***/ "containers/InsertMediaModal/InsertMediaModal":
/*!***********************************!*\
  !*** external "InsertMediaModal" ***!
  \***********************************/
/***/ (function(module) {

module.exports = InsertMediaModal;

/***/ }),

/***/ "url":
/*!**************************!*\
  !*** external "NodeUrl" ***!
  \**************************/
/***/ (function(module) {

module.exports = NodeUrl;

/***/ }),

/***/ "prop-types":
/*!****************************!*\
  !*** external "PropTypes" ***!
  \****************************/
/***/ (function(module) {

module.exports = PropTypes;

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = React;

/***/ }),

/***/ "react-dom/client":
/*!*********************************!*\
  !*** external "ReactDomClient" ***!
  \*********************************/
/***/ (function(module) {

module.exports = ReactDomClient;

/***/ }),

/***/ "react-redux":
/*!*****************************!*\
  !*** external "ReactRedux" ***!
  \*****************************/
/***/ (function(module) {

module.exports = ReactRedux;

/***/ }),

/***/ "reactstrap":
/*!*****************************!*\
  !*** external "Reactstrap" ***!
  \*****************************/
/***/ (function(module) {

module.exports = Reactstrap;

/***/ }),

/***/ "redux":
/*!************************!*\
  !*** external "Redux" ***!
  \************************/
/***/ (function(module) {

module.exports = Redux;

/***/ }),

/***/ "classnames":
/*!*****************************!*\
  !*** external "classnames" ***!
  \*****************************/
/***/ (function(module) {

module.exports = classnames;

/***/ }),

/***/ "i18n":
/*!***********************!*\
  !*** external "i18n" ***!
  \***********************/
/***/ (function(module) {

module.exports = i18n;

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ (function(module) {

module.exports = jQuery;

/***/ }),

/***/ "qs":
/*!*********************!*\
  !*** external "qs" ***!
  \*********************/
/***/ (function(module) {

module.exports = qs;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!**************************************!*\
  !*** ./client/src/bundles/bundle.js ***!
  \**************************************/


__webpack_require__(/*! boot */ "./client/src/boot/index.js");
__webpack_require__(/*! entwine/JsonField */ "./client/src/entwine/JsonField.js");
}();
/******/ })()
;
//# sourceMappingURL=bundle.js.map