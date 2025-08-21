import React, { useEffect, useState } from 'react';
import { View, FlatList, Pressable, Text, RefreshControl } from 'react-native';
import NotificationItem from '../../components/NotificationItem';
import EmptyState from '../../components/EmptyState';
import { Api } from '../../lib/api';
import { AppNotification } from '../../types';
import { Ionicons } from '@expo/vector-icons';

export default function Notifications() {
  const [items, setItems] = useState<AppNotification[]>([]);
  const [refreshing, setRefreshing] = useState(false);

  const load = async () => {
    try {
      const data = await Api.getNotifications(); // mocked fallback if API missing
      setItems(data);
    } catch {}
  };

  useEffect(() => { load(); }, []);

  const onRefresh = async () => {
    setRefreshing(true);
    try { await load(); } finally { setRefreshing(false); }
  };

  const clearAll = () => setItems([]);

  return (
    <View style={{ flex: 1, padding: 12 }}>
      <View style={{ flexDirection: 'row', alignItems: 'center', marginBottom: 8 }}>
        <Text style={{ fontSize: 18, fontWeight: '700', flex: 1 }}>Notifications</Text>
        <Pressable onPress={clearAll} hitSlop={12}><Ionicons name="trash-outline" size={20} /></Pressable>
      </View>

      <FlatList
        data={items}
        keyExtractor={(it) => String(it.id)}
        renderItem={({ item }) => <NotificationItem item={item} />}
        ListEmptyComponent={<EmptyState title="No notifications" subtitle="You're all caught up." />}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
        contentContainerStyle={{ gap: 8 }}
      />
    </View>
  );
}
