import React, { useEffect, useMemo, useState } from 'react';
import { View, TextInput, FlatList, ActivityIndicator, RefreshControl, Text } from 'react-native';
import { Api, toAbsoluteUrl } from '../../lib/api';
import { colors } from '../../constants/colors';
import { Product } from '../../types';
import ProductCard from '../../components/ProductCard';
import EmptyState from '../../components/EmptyState';
import { useCart } from '../../stores/cart';

export default function Marketplace() {
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [items, setItems] = useState<Product[]>([]);
  const { add } = useCart();

  const load = async () => {
    setLoading(true);
    try {
      const data = await Api.getProducts();
      setItems(data);
    } finally { setLoading(false); }
  };

  useEffect(() => { load(); }, []);

  const onRefresh = async () => {
    setRefreshing(true);
    try { await load(); } finally { setRefreshing(false); }
  };

  const filtered = useMemo(() => {
    const q = search.trim().toLowerCase();
    if (!q) return items;
    return items.filter(p =>
      [p.name, p.description, p.category].filter(Boolean).some(s => String(s).toLowerCase().includes(q))
    );
  }, [search, items]);

  return (
    <View style={{ flex: 1, padding: 12, gap: 12 }}>
      <TextInput
        placeholder="Search"
        value={search}
        onChangeText={setSearch}
        style={{
          backgroundColor: 'white', borderRadius: 10, paddingHorizontal: 14, paddingVertical: 12,
          borderWidth: 1, borderColor: '#e5e7eb'
        }}
      />

      {loading ? (
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
          <ActivityIndicator color={colors.green} />
        </View>
      ) : (
        <FlatList
          data={filtered}
          keyExtractor={(p) => String(p.productid)}
          numColumns={2}
          columnWrapperStyle={{ justifyContent: 'space-between' }}
          renderItem={({ item }) => (
            <ProductCard
              product={{ ...item, imageurl: toAbsoluteUrl(item.imageurl) }}
              onAdd={() => add(item, 1)}
            />
          )}
          ListEmptyComponent={<EmptyState title="No products found" subtitle="Try another keyword." />}
          refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
          contentContainerStyle={{ paddingBottom: 14 }}
        />
      )}
    </View>
  );
}
