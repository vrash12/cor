import React, { useMemo } from 'react';
import { View, FlatList, Text, Pressable } from 'react-native';
import { useCart } from '../../stores/cart';
import CartItemRow from '../../components/CartItemRow';
import EmptyState from '../../components/EmptyState';
import { colors } from '../../constants/colors';

export default function Cart() {
  const { items, clear, total, shipping, checkout } = useCart();
  const list = useMemo(() => Object.values(items), [items]);
  const subtotal = total();
  const shippingFee = shipping();
  const grandTotal = subtotal + shippingFee;

  return (
    <View style={{ flex: 1 }}>
      <FlatList
        data={list}
        keyExtractor={(it) => String(it.product.productid)}
        renderItem={({ item }) => <CartItemRow item={item} />}
        ListEmptyComponent={<EmptyState title="Your cart is empty" subtitle="Browse products and add to cart." />}
        contentContainerStyle={{ padding: 12, gap: 10 }}
      />

      {list.length > 0 && (
        <View style={{
          borderTopWidth: 1, borderColor: '#e5e7eb', padding: 12, backgroundColor: 'white'
        }}>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 6 }}>
            <Text style={{ color: '#6b7280' }}>Subtotal</Text>
            <Text>₱{subtotal.toFixed(2)}</Text>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 6 }}>
            <Text style={{ color: '#6b7280' }}>Shipping fee</Text>
            <Text>₱{shippingFee.toFixed(2)}</Text>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 12 }}>
            <Text style={{ fontWeight: '700' }}>Total</Text>
            <Text style={{ fontWeight: '700' }}>₱{grandTotal.toFixed(2)}</Text>
          </View>

          <View style={{ flexDirection: 'row', gap: 10 }}>
            <Pressable onPress={clear} style={{
              flex: 1, padding: 12, borderRadius: 10, borderWidth: 1, borderColor: '#e5e7eb', alignItems: 'center'
            }}>
              <Text>Clear</Text>
            </Pressable>
            <Pressable onPress={checkout} style={{
              flex: 1, padding: 12, borderRadius: 10, backgroundColor: colors.green, alignItems: 'center'
            }}>
              <Text style={{ color: 'white', fontWeight: '700' }}>Check out</Text>
            </Pressable>
          </View>
        </View>
      )}
    </View>
  );
}
