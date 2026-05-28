// ============================================================
// Job Seeker Dashboard
// ============================================================
import React, { useCallback, useState } from 'react';
import {
  View, Text, StyleSheet, ScrollView, TouchableOpacity, RefreshControl,
} from 'react-native';
import { useRouter, useFocusEffect } from 'expo-router';
import { Card, StatusBadge, EmptyState } from '../../src/components/ui';
import { api, getApiError } from '../../src/api/client';
import { useAuth } from '../../src/context/AuthContext';
import { Colors, Spacing, FontSize, Radius, Shadow } from '../../src/constants/theme';
import { confirmAction } from '../../src/utils/confirm';

export default function SeekerDashboard() {
  const router = useRouter();
  const { user, logout } = useAuth();
  const [profile, setProfile] = useState<any>(null);
  const [skills, setSkills] = useState<any[]>([]);
  const [applications, setApplications] = useState<any[]>([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [refreshing, setRefreshing] = useState(false);

  const loadData = async () => {
    try {
      const [pRes, aRes] = await Promise.all([
        api.get('/job-seeker/profile'),
        api.get('/applications/my-applications'),
      ]);
      setProfile(pRes.data.profile);
      setSkills(pRes.data.skills || []);
      setApplications(aRes.data.applications || []);
    } catch (err: any) {
      console.warn('Dashboard load error:', getApiError(err));
    }
  };

  useFocusEffect(useCallback(() => { loadData(); }, []));

  const onRefresh = async () => {
    setRefreshing(true);
    await loadData();
    setRefreshing(false);
  };

  const handleLogout = () => {
    confirmAction(
      'Sign out',
      'Are you sure you want to sign out?',
      async () => {
        await logout();
        router.replace('/');
      },
      'Sign out',
      true,
    );
  };

  const displayName = `${profile?.first_name || 'Job Seeker'} ${profile?.last_name || ''}`.trim();

  return (
    <ScrollView
      style={styles.container}
      contentContainerStyle={styles.content}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={Colors.primary} />}
      testID="seeker-dashboard"
    >
      <View style={styles.header}>
        <View>
          <Text style={styles.kicker}>PESO-Link MisOr</Text>
          <Text style={styles.headerTitle}>Overview</Text>
        </View>
        <TouchableOpacity onPress={handleLogout} testID="seeker-logout" style={styles.avatar}>
          <Text style={styles.avatarText}>Exit</Text>
        </TouchableOpacity>
      </View>

      <View style={styles.body}>
        <Card style={styles.welcomeCard}>
          <Text style={styles.welcomeName}>Hello, {displayName}!</Text>
          <Text style={styles.welcomeSub}>
            {!profile?.profile_completed
              ? 'Complete your profile to start applying for jobs.'
              : 'Your profile is complete and ready.'}
          </Text>
          {!profile?.profile_completed && (
            <TouchableOpacity testID="complete-profile" onPress={() => router.push('/(seeker)/profile')} style={styles.noticePill}>
              <Text style={styles.noticePillText}>Complete Profile</Text>
            </TouchableOpacity>
          )}
        </Card>

        <View style={styles.primaryActions}>
          <ActionTile
            testID="action-jobs"
            label="Find Jobs"
            icon="?"
            primary
            onPress={() => router.push('/(seeker)/jobs')}
          />
          <ActionTile
            testID="action-applications"
            label="My Applications"
            icon="D"
            onPress={() => router.push('/(seeker)/my-applications')}
          />
        </View>



        <Text style={styles.sectionTitle}>Quick Actions</Text>
        <View style={styles.actionList}>
          <ActionButton testID="action-profile" label="My Profile and Skills" onPress={() => router.push('/(seeker)/profile')} />
        </View>

        <Text style={styles.sectionTitle}>Recent Applications</Text>
        {applications.length === 0 ? (
          <EmptyState message="You haven't applied to any jobs yet." />
        ) : (
          <Card style={styles.applicationsCard}>
            {applications.slice(0, 3).map((a) => (
              <TouchableOpacity key={a.id} onPress={() => router.push(`/(seeker)/job/${a.job_post_id}`)} testID={`app-card-${a.id}`} style={styles.applicationItem}>
                <View style={styles.companyMark}>
                  <Text style={styles.companyMarkText}>{(a.company_name || 'P').charAt(0).toUpperCase()}</Text>
                </View>
                <View style={{ flex: 1, marginRight: 8 }}>
                  <Text style={styles.applicationTitle}>{a.job_title}</Text>
                  <Text style={styles.applicationCompany}>{a.company_name}</Text>
                  <Text style={styles.applicationDate}>Applied {new Date(a.applied_at).toLocaleDateString()}</Text>
                </View>
                <StatusBadge status={a.application_status} />
              </TouchableOpacity>
            ))}
          </Card>
        )}
      </View>
    </ScrollView>
  );
}



function ActionTile({
  label, icon, onPress, testID, primary,
}: { label: string; icon: string; onPress: () => void; testID?: string; primary?: boolean }) {
  return (
    <TouchableOpacity testID={testID} onPress={onPress} activeOpacity={0.78} style={[styles.actionTile, primary && styles.actionTilePrimary]}>
      <Text style={[styles.actionTileIcon, primary && styles.actionTileIconPrimary]}>{icon}</Text>
      <Text style={[styles.actionTileText, primary && styles.actionTileTextPrimary]}>{label}</Text>
    </TouchableOpacity>
  );
}

function ActionButton({ label, onPress, testID }: { label: string; onPress: () => void; testID?: string }) {
  return (
    <TouchableOpacity testID={testID} onPress={onPress} activeOpacity={0.75} style={styles.actionBtn}>
      <Text style={styles.actionText}>{label}</Text>
      <Text style={styles.actionArrow}>{'>'}</Text>
    </TouchableOpacity>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: Colors.primaryDark },
  content: { backgroundColor: Colors.lightBg, paddingBottom: Spacing.xl },
  header: {
    backgroundColor: Colors.primaryDark,
    paddingHorizontal: Spacing.lg,
    paddingTop: Spacing.lg,
    paddingBottom: Spacing.md,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  kicker: { color: Colors.cardHighlight, fontSize: FontSize.xs, fontWeight: '900' },
  headerTitle: { color: Colors.white, fontSize: FontSize.xl, fontWeight: '900', marginTop: 4 },
  avatar: {
    width: 46, height: 46, borderRadius: 23,
    backgroundColor: Colors.surface,
    alignItems: 'center', justifyContent: 'center',
  },
  avatarText: { color: Colors.gray, fontSize: FontSize.xs, fontWeight: '800' },
  body: { padding: Spacing.md },
  welcomeCard: {
    padding: Spacing.lg,
    borderRadius: Radius.xl,
    marginBottom: Spacing.md,
  },
  welcomeName: { fontSize: FontSize.lg, fontWeight: '900', color: Colors.textDark },
  welcomeSub: { fontSize: FontSize.sm, color: Colors.gray, marginTop: 8, lineHeight: 20 },

  noticePill: {
    alignSelf: 'flex-start',
    backgroundColor: '#FEF3C7',
    borderColor: Colors.warning,
    borderWidth: 1,
    borderRadius: Radius.pill,
    paddingHorizontal: 14,
    paddingVertical: 8,
    marginTop: Spacing.md,
  },
  noticePillText: { color: '#92400E', fontSize: FontSize.xs, fontWeight: '900' },
  primaryActions: { flexDirection: 'row', gap: Spacing.md, marginBottom: Spacing.md },
  actionTile: {
    flex: 1,
    minHeight: 92,
    backgroundColor: Colors.white,
    borderColor: Colors.borderSoft,
    borderWidth: 1,
    borderRadius: Radius.lg,
    alignItems: 'center',
    justifyContent: 'center',
    padding: Spacing.md,
  },
  actionTilePrimary: { backgroundColor: Colors.primary, borderColor: Colors.primary },
  actionTileIcon: { color: Colors.primary, fontSize: FontSize.xl, fontWeight: '900', marginBottom: 6 },
  actionTileIconPrimary: { color: Colors.white },
  actionTileText: { color: Colors.textDark, fontSize: FontSize.sm, fontWeight: '900', textAlign: 'center' },
  actionTileTextPrimary: { color: Colors.white },

  sectionTitle: { fontSize: FontSize.md, fontWeight: '900', color: Colors.textDark, marginBottom: Spacing.sm, marginTop: Spacing.sm },
  actionList: { gap: Spacing.sm, marginBottom: Spacing.lg },
  actionBtn: {
    backgroundColor: Colors.white,
    borderColor: Colors.borderSoft,
    borderWidth: 1,
    borderRadius: Radius.md,
    minHeight: 52,
    paddingVertical: 14,
    paddingHorizontal: 16,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  actionText: { color: Colors.textDark, fontSize: FontSize.md, fontWeight: '800' },
  actionArrow: { color: Colors.primary, fontSize: FontSize.lg, fontWeight: '900' },
  applicationsCard: { padding: Spacing.sm },
  applicationItem: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.surface,
    borderColor: Colors.borderSoft,
    borderWidth: 1,
    borderRadius: Radius.lg,
    padding: Spacing.sm,
    marginBottom: Spacing.sm,
  },
  companyMark: {
    width: 52, height: 52, borderRadius: Radius.md,
    backgroundColor: Colors.cardHighlight,
    alignItems: 'center', justifyContent: 'center',
    marginRight: Spacing.md,
  },
  companyMarkText: { color: Colors.primary, fontWeight: '900', fontSize: FontSize.xl },
  applicationTitle: { fontWeight: '900', color: Colors.textDark, fontSize: FontSize.md },
  applicationCompany: { color: Colors.gray, fontSize: FontSize.sm, marginTop: 2 },
  applicationDate: { color: Colors.gray, fontSize: FontSize.xs, marginTop: 4 },
});
