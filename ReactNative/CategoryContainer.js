import React, { useEffect, useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, TextInput, View, ScrollView, Button, Alert, ActivityIndicator, Image, SafeAreaView, ImageBackground } from 'react-native';
import axios from 'axios';
import Product from './Category/Product';

const BASE_URL = 'http://192.168.2.24:8081';
type CategoryContainerProps = {
    url: String,
    category_id: Number,
    name: String,
};

const CategoryContainer = (props: CategoryContainerProps) => {

    const { category_id, url, name } = props;
    const [products, setProducts] = useState(null);
    const [productsLoaded, setProductsLoaded] = useState(false);
    
    // use useEffect
    useEffect(() => {
        const apiUrl = BASE_URL + '/api/categories/' + category_id + '/products';
        axios.get(apiUrl, Config).then(response => {
            if (response.data == undefined) {
                return;
            }
            const { category, products } = response.data;
            setProducts(products);
            setProductsLoaded(true);
        }).catch(error => {
            console.log('apiUrl error', error);
        }).finally(() => {
        });

        return () => { };
    }, [productsLoaded]);

    let productComponents = [];
    if (products) {
        for (let i = 0; i < products.length; i++) {
            productComponents.push(<Product product={products[i]} />);    
        }
    }

    return (
        <SafeAreaView>
            <Text>{name}</Text>
            {!products && <Text>Loading products...</Text>}
            {products && productComponents}
        </SafeAreaView>
    )
}

const Config = {
    headers: {
        Authorization: `Bearer <TOKEN>`
    }
};

export default CategoryContainer;
