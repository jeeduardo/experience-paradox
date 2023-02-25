import { StyleSheet } from 'react-native';

const Styles = StyleSheet.create({
    container: {
      flex: 1,
      backgroundColor: '#fff',
      // alignItems: 'center',
      justifyContent: 'center',
    },
    image: {
      width: 200,
      height: 200
    },
    flex: {
        display: 'flex',
        flexDirection: 'row',
        justifyContent: 'center',
        alignContent: 'center'
    },
    imageContainer: {
        display: 'flex',
        flexDirection: 'row',
        justifyContent: 'center',
        alignItems: 'center'
    },
    imageText: {
        color: '#000',
        fontSize: 14,
        margin: 10,
        textAlign: 'center',
        verticalAlign: 'bottom'
    },
    productImage: {
       alignContent: 'center',
       height: 200,
       width: 200,
       margin: 'auto'
    },
    categoryWhite: {
      color: '#fff',
      display: 'flex',
      textAlign: 'center',
      textTransform: 'uppercase',
      fontSize: 24
    },
    label: {
      fontSize: 20,
      textAlign: 'left',
      margin: 10,
    },
    button: {
      backgroundColor: '#003060',
      color: '#fff'
    },
    input: {
        borderColor: '#000', 
        borderWidth: 1,
        fontSize: 20,
        width: '90%',
    },
    passwordInput: {
      fontSize: 20,
      width: '90%'
    },
    categoryImage: {
      marginBottom: 8,
      height: 300,
      width: 300
    }
  });

  export default Styles;