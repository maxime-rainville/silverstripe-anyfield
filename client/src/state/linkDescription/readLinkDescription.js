/* eslint-disable */
import { graphqlTemplates } from 'lib/Injector';

const apolloConfig = {
  props(
    props
  ) {
    const {
      data: {
        error,
        readLinkDescription,
        loading: networkLoading,
      },
    } = props;
    const errors = error && error.graphQLErrors &&
      error.graphQLErrors.map((graphQLError) => graphQLError.message);

    return {
      loading: networkLoading,
      linkDescriptions: readLinkDescription || [],
      graphQLErrors: errors,
    };
  },
};

const { READ } = graphqlTemplates;
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
  fields: ['id', 'description', 'title'],
};
export default query;
