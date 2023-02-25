import React, { useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, TextInput, View, ScrollView, Button, Alert, ActivityIndicator, Image, SafeAreaView, ImageBackground } from 'react-native';
import Category from './Category';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import CategoryContainer from './CategoryContainer';
import UserRegistration from './UserRegistration';

const Stack = createNativeStackNavigator();

const App = () => {

  
  const categories = [
    { category_id: 1, url: 'cookies', name: 'Cookies'},
    { category_id: 2, url: 'cakes', name: 'Cakes'},
    { category_id: 3, url: 'coffee', name: 'Coffee'}
  ];
  let stackCategoryScreens = [];

  for (let i = 0; i < categories.length; i++) {
    stackCategoryScreens.push(
      <Stack.Screen name={categories[i].name} component={CategoryScreen} options={{ title: categories[i].name }} />
    )
  }

  // <Stack.Screen name="Category" component={CategoryScreen} options={{ title: 'hey title??'}} />
  return (

    <NavigationContainer>
       <Stack.Navigator>
         <Stack.Screen name="Home" component={HomeScreen} />
         {stackCategoryScreens}
         <Stack.Screen name="UserRegistration" component={UserRegistration} />
       </Stack.Navigator>
    </NavigationContainer>
  )
}

const CategoryScreen = ({navigation, route}) => {
  let { category_id, name, url } = route.params;

  if (!name) {
    name = 'NO NAME';
  }
  return (
    <SafeAreaView>
      <ScrollView>
        <Text style={styles.label}>Home > {name}</Text>
        <CategoryContainer category_id={category_id} url={url} name={name} />
      </ScrollView>
    </SafeAreaView>
  )
}

const HomeScreen = ({navigation}) => {
  // @todo: ajax this...
  const categories = [
    { category_id: 1, url: 'cookies', name: 'Cookies', image_path: '/images/chocolate-chip-cookies.jpeg'},
    { category_id: 2, url: 'cakes', name: 'Cakes', image_path: '/images/belgian-dark-chocolate-ganache-cake.jpeg'},
    { category_id: 3, url: 'coffee', name: 'Coffee', image_path: '/images/coffee.jpeg'}
  ];
  let categoryComponents = [];

  for (let i = 0; i < categories.length; i++) {
    categoryComponents.push(
      <Category key={categories[i].url} 
      categoryData={categories[i]}
      styleClasses={styles} navigation={navigation}></Category>
    )
  }

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView>
        <Text style={styles.label}>Cookies! Cookies!</Text>
        {categoryComponents}
        <Button title='Register' onPress={() => navigation.navigate('UserRegistration')} />
      </ScrollView>
    </SafeAreaView>
  )

}

const OldApp = () => {

  const initialUserState = {
    username: '',
    email: '',
    telephone: '',
  };

  const initialPasswordData = { original: '', confirmation: '' };

  const [userData, setUserData] = useState(initialUserState);
  const [passwordData, setPasswordData] = useState(initialPasswordData);

  const setUsernameFn = (value) => {
    setSampleState(value);
  };

  const setData = (key, value) => {
    let newUserData = userData;
    newUserData[key] = value;
    setUserData(newUserData);
  };

  return (
    <SafeAreaView style={styles.container}>
      <Image style={styles.image} source={require('./assets/tom-and-jerry-sabog.png')} />
      <Image source={{ 
        width: 200,
        height: 200,
        uri: "https://picsum.photos/200" }} />
      <Text numberOfLines={2}
        onPress={() => alert('o ha ')}>Text na mahaba kaso maikli na</Text>
      <FormInput name="username"
                  label="Username"
                  changeFn={setData}/>
      <FormInput name="email"
                  label="E-mail address" inputMode="email"
                  changeFn={setData}/>

      <FormInput name="fullname" label="Full name" inputMode="text" placeholder="Full Name" />
      <FormInput name="street" label="Street" inputMode="text" placeholder="Street address" />
      <FormInput name="telephone"
                  label="Phone number"
                  inputMode="tel"
                  placeholder="Phone Number" changeFn={setData} />

      <PasswordInput name="password" placeholder="Enter password" />
      <PasswordInput name="password_confirmation" placeholder="Confirm password" />
      <Button
        style={styles.button}
        title="Click Me"
        onPress={() => Register(userData)}/> 
      <StatusBar style="auto" />
    </SafeAreaView>
  );
}

type FormInputProps = {
  name: string;
  label: string;
  changeFn: function;
}
const FormInput = (props: FormInputProps) => {
  let changeFn = props.changeFn;
  let inputMode = 'text';
  if (props.inputMode) {
    inputMode = props.inputMode; // tel, email, etc...
  }

  const onChangeFn = (newValue) => {
    if (props.changeFn) {
      props.changeFn(props.name, newValue);
    }
  }
  return (
    <View>
      <TextInput
        style={styles.input}
        name={props.name}
        inputMode={inputMode}
        placeholder={props.label}
        onChangeText={newValue => { onChangeFn(newValue) }}/>
    </View>
  )
}

type PasswordInputProps = {
  name: string;
  placeholder: string;
};
const PasswordInput = (props: PasswordInputProps) => {
  return (
    <View>
      <TextInput
        style={styles.passwordInput}
        name={props.name}
        placeholder={props.placeholder}
        autoCapitalize="none"
        autoCorrect={false}
        textContentType="newPassword"
        secureTextEntry
        enablesReturnKeyAutomatically />
    </View>
  )
};

const Register = (userData) => {
  const utcDate = new Date();
};

const styles = StyleSheet.create({
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
    fontSize: 20,
    width: '90%',
  },
  passwordInput: {
    fontSize: 20,
    width: '90%'
  },
  categoryImage: {
    marginBottom: 8
  }
});

export default App;
