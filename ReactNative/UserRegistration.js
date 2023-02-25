import React, { useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, TextInput, View, ScrollView, Button, Alert, ActivityIndicator, Image, SafeAreaView, ImageBackground } from 'react-native';
import Styles from './Styles';

const UserRegistration = ({ navigation }) => {
    
    const [firstname, setFirstname] = useState('');
    const [lastname, setLastname] = useState('');
    const [email, setEmail] = useState('');
    const [telephone, setTelephone] = useState('');
    const [password, setPassword] = useState('');

    const validateAndSaveUser = () => {
        console.log('firstname, lastname, email, password', firstname, lastname, email, password);
    };
    
    return (
        <SafeAreaView>
            <ScrollView>
                <TextInput placeholder="First Name" onChangeText={setFirstname} style={Styles.input}/>

                <TextInput placeholder="Last Name" onChangeText={setLastname} style={Styles.input} />

                <TextInput placeholder="E-mail address" onChangeText={setEmail} style={Styles.input} />

                <TextInput placeholder="Phone number" onChangeText={setTelephone} style={Styles.input} keyboardType="numeric" />

                {/* @todo: Password field? What to do?*/}
                <TextInput placeholder="Password" onChangeText={setPassword} secureTextEntry={true} />

                <TextInput placeholder="Confirm Password" secureTextEntry={true} />
                <Button title="Register" onPress={() => navigation.navigate('Home')} />
            </ScrollView>
        </SafeAreaView>
    )
}

export default UserRegistration;