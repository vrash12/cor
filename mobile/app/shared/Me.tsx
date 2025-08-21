import React from 'react';
import { View, Text, Image, Pressable } from 'react-native';
import { useAuth } from '../../stores/auth';
import SectionButton from '../../components/SectionButton';

export default function Me({ variant }: { variant: 'customer'|'farmer'|'cooperativeadmin' }) {
  const { user, logout } = useAuth();
  const name = user?.name ?? 'Farm2Go User';

  return (
    <View style={{ flex: 1, backgroundColor: '#fff' }}>
      <View style={{ padding: 16, flexDirection: 'row', gap: 12, alignItems: 'center', borderBottomWidth: 8, borderColor: '#eee' }}>
        <Image source={{ uri: 'https://placehold.co/100x100?text=👤' }} style={{ width: 64, height: 64, borderRadius: 32 }} />
        <View style={{ flex: 1 }}>
          <Text style={{ fontWeight: '700', fontSize: 16 }}>{name}</Text>
          <Text style={{ color: '#666' }}>0 Follower 1 Following</Text>
        </View>
        <Pressable><Text>⚙️</Text></Pressable>
      </View>

      <View style={{ flexDirection: 'row', borderBottomWidth: 8, borderColor: '#eee' }}>
        <SectionButton label="To pay" icon="card-outline" />
        <SectionButton label="To Deliver" icon="cube-outline" />
        <SectionButton label="To Receive" icon="trail-sign-outline" />
        <SectionButton label="Sales History" icon="time-outline" />
        <SectionButton label="Rate" icon="star-outline" />
      </View>

      <View style={{ flexDirection: 'row', borderBottomWidth: 8, borderColor: '#eee' }}>
        <SectionButton label="Settings" icon="settings-outline" />
        <SectionButton label="Notification" icon="notifications-outline" />
        <SectionButton label="Log Out" icon="log-out-outline" onPress={logout} />
      </View>

      {/* Customer-specific can go here later */}
      {variant === 'customer' ? null : null}
    </View>
  );
}
