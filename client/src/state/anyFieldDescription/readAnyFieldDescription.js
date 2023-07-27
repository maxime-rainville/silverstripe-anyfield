/* eslint-disable */
import { graphqlTemplates } from 'lib/Injector';

const apolloConfig = {
  props(
    props
  ) {
    const {
      data: {
        error,
        readAnyFieldDescription,
        loading: networkLoading,
      },
    } = props;
    const errors = error && error.graphQLErrors &&
      error.graphQLErrors.map((graphQLError) => graphQLError.message);
    return {
      loading: networkLoading,
      anyFieldDescriptions: readAnyFieldDescription || [],
      graphQLErrors: errors,
    };
  },
};

const { READ } = graphqlTemplates;
const query = {
  apolloConfig,
  templateName: READ,
  pluralName: 'AnyFieldDescription',
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
