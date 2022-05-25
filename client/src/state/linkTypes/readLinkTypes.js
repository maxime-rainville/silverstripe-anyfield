/* eslint-disable */
import { graphqlTemplates } from 'lib/Injector';

const apolloConfig = {
  props(
    props
  ) {
    const {
      data: {
        error,
        readLinkTypes,
        loading: networkLoading,
      },
    } = props;
    const errors = error && error.graphQLErrors &&
      error.graphQLErrors.map((graphQLError) => graphQLError.message);

    const types = readLinkTypes ?
      readLinkTypes.reduce((accumulator, type) => (
        { ...accumulator, [type.key]: type }
      ), {}) :
      {};

    return {
      loading: networkLoading,
      types,
      graphQLErrors: errors,
    };
  },
};

const { READ } = graphqlTemplates;
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
  fields: ['key', 'title', 'handlerName', 'icon'],
};
export default query;
