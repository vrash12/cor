import React from 'react';
import { View, Text, Image, Pressable } from 'react-native';
import { CartItem } from '../stores/cart';
import QuantityStepper from './QuantityStepper';
import { Ionicons } from '@expo/vector-icons';

export default function CartItemRow({ item }: { item: CartItem }) {
  const { product, qty, inc, dec, remove } = item;

  return (
    <View style={{
      flexDirection: 'row', backgroundColor: 'white', borderRadius: 12,
      borderWidth: 1, borderColor: '#e5e7eb', overflow: 'hidden'
    }}>
      <Image
        source={{ uri: product.imageurl || 'https://placehold.co/120x120?text=Img' }}
        style={{ width: 90, height: 90 }}
      />
      <View style={{ flex: 1, padding: 10 }}>
        <Text style={{ fontWeight: '700' }} numberOfLines={1}>{product.name}</Text>
        <Text style={{ color: '#6b7280', marginTop: 2 }} numberOfLines={2}>{product.description || '—'}</Text>

        <View style={{ flexDirection: 'row', marginTop: 6, alignItems: 'center', justifyContent: 'space-between' }}>
          <Text style={{ fontWeight: '700' }}>₱{Number(product.price || 0).toFixed(2)}</Text>
          <QuantityStepper value={qty} onInc={inc} onDec={dec} />
        </View>
      </View>

      <Pressable onPress={remove} style={{ padding: 8, alignItems: 'center', justifyContent: 'center' }}>
        <Ionicons name="trash-outline" size={18} color="#ef4444" />
      </Pressable>
    </View>
  );
}
