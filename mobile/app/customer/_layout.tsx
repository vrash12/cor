import { Tabs } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';

const green = '#198754';

export default function CustomerTabs() {
  return (
    <Tabs
      screenOptions={{
        tabBarActiveTintColor: green,
        headerTitleStyle: { fontWeight: '700' },
      }}
    >
      <Tabs.Screen
        name="marketplace"
        options={{
          title: 'For You',
          tabBarIcon: (p) => <Ionicons name="heart-outline" {...p} />,
        }}
      />
      <Tabs.Screen
        name="cart"
        options={{
          title: 'My Cart',
          tabBarIcon: (p) => <Ionicons name="cart-outline" {...p} />,
        }}
      />
      <Tabs.Screen
        name="notifications"
        options={{
          title: 'Notification',
          tabBarIcon: (p) => <Ionicons name="notifications-outline" {...p} />,
        }}
      />
      <Tabs.Screen
        name="me"
        options={{
          title: 'Me',
          tabBarIcon: (p) => <Ionicons name="person-outline" {...p} />,
        }}
      />
    </Tabs>
  );
}
