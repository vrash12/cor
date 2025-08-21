import React from 'react';
import { View, Image, Text, Pressable } from 'react-native';
import { Product } from '../types';
import { colors } from '../constants/colors';
import { Ionicons } from '@expo/vector-icons';

export default function ProductCard({
  product,
  onAdd,
}: {
  product: Product;
  onAdd: () => void;
}) {
  return (
    <View style={{
      width: '48%', backgroundColor: 'white', borderRadius: 12,
      overflow: 'hidden', borderWidth: 1, borderColor: '#e5e7eb', marginBottom: 12
    }}>
      <Image
        source={{ uri: product.imageurl || 'https://placehold.co/600x400?text=Img' }}
        style={{ width: '100%', height: 120 }}
      />
      <View style={{ padding: 10, gap: 6 }}>
        <Text numberOfLines={1} style={{ fontWeight: '700' }}>{product.name}</Text>
        <Text numberOfLines={2} style={{ color: '#6b7280', fontSize: 12 }}>{product.description || '—'}</Text>
        <Text style={{ fontWeight: '700' }}>₱{Number(product.price || 0).toFixed(2)}</Text>

        <Pressable
          onPress={onAdd}
          style={{
            alignSelf: 'stretch', backgroundColor: colors.green, paddingVertical: 8,
            borderRadius: 10, alignItems: 'center', flexDirection: 'row', justifyContent: 'center', gap: 6
          }}
        >
          <Ionicons name="add-circle-outline" color="white" size={16} />
          <Text style={{ color: 'white', fontWeight: '700' }}>Add to Cart</Text>
        </Pressable>
      </View>
    </View>
  );
}
