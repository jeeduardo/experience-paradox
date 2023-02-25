import React, { useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, TextInput, View, ScrollView, Button, Alert, ActivityIndicator, Image, SafeAreaView, ImageBackground } from 'react-native';
import Styles from './Styles';
import Config from './Config';

type CategoryProps = {
    url: String;
    name: String;
    styleClass: Object;
}
const Category = (props: CategoryProps) => {
    const { styleClasses, categoryData, navigation } = props;
    const { url, name, category_id, image_path } = categoryData;
    const { API_BASE_URL } = Config;
    // default image_path?? https://picsum.photos/400
    const uri = API_BASE_URL + image_path;
    return (
        <View style={Styles.flex}>
            <ImageBackground style={Styles.categoryImage}
                source={{ 
                width: 400,
                height: 400,
                uri }}
            >
                <Text style={styleClasses.categoryWhite} 
                    onPress={() => navigation.navigate(name, { category_id, name, url })}>{name}</Text>

            </ImageBackground>
        </View>
    )
}

export default Category;